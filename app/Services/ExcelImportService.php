<?php

namespace App\Services;

use App\Models\ImportBatch;
use App\Models\ImportError;
use App\Imports\SystemMasterImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Exception;

class ExcelImportService
{
    public function import($file)
    {
        $originalName = $file->getClientOriginalName();
        $dateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d\TH:i:s.v') . "')");
        
        $batchId = DB::table('import_batches')->insertGetId([
            'filename' => $originalName,
            'status' => 'processing',
            'created_at' => $dateRaw,
            'updated_at' => $dateRaw
        ]);

        $batch = ImportBatch::find($batchId);

        try {
            $masterImport = new SystemMasterImport($batch->id);
            
            Excel::import($masterImport, $file);

            $summary = [];
            $totalInserted = 0;
            $totalUpdated = 0;
            $totalIgnored = 0;
            $totalErrors = 0;

            foreach ($masterImport->sheets as $sheetName => $sheetInstance) {
                $results = $sheetInstance->getResults();
                
                $summary[$sheetName] = [
                    'inserted' => $results['inserted'],
                    'updated' => $results['updated'],
                    'ignored' => $results['ignored'],
                    'error_count' => count($results['errors'])
                ];

                $totalInserted += $results['inserted'];
                $totalUpdated += $results['updated'];
                $totalIgnored += $results['ignored'];
                $totalErrors += count($results['errors']);

                $errorData = [];
                $chunkDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d\TH:i:s.v') . "')");
                
                foreach ($results['errors'] as $error) {
                    $errorData[] = [
                        'import_batch_id' => $batch->id,
                        'sheet_name' => $sheetName,
                        'row_number' => $error['row_number'],
                        'error_message' => $this->sanitizeUtf8($error['error_message']),
                        'row_data' => json_encode(
                            $this->sanitizeUtf8($error['row_data']),
                            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
                        ),
                        'created_at' => $chunkDateRaw,
                        'updated_at' => $chunkDateRaw
                    ];
                }

                if (!empty($errorData)) {
                    foreach (array_chunk($errorData, 100) as $chunk) {
                        ImportError::insert($chunk);
                    }
                }
            }

            $updateDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d\TH:i:s.v') . "')");
            DB::table('import_batches')->where('id', $batch->id)->update([
                'status' => 'completed',
                'total_rows' => $totalInserted + $totalUpdated + $totalIgnored + $totalErrors,
                'inserted_rows' => $totalInserted,
                'updated_rows' => $totalUpdated,
                'ignored_rows' => $totalIgnored,
                'error_rows' => $totalErrors,
                'summary' => json_encode($summary, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE),
                'updated_at' => $updateDateRaw
            ]);

            return ImportBatch::find($batch->id);

        } catch (Exception $e) {
            $errorDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d\TH:i:s.v') . "')");
            DB::table('import_batches')->where('id', $batch->id)->update([
                'status' => 'failed',
                'summary' => json_encode(
                    ['exception' => $this->sanitizeUtf8($e->getMessage())],
                    JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
                ),
                'updated_at' => $errorDateRaw
            ]);

            throw $e;
        }
    }

    /**
     * Sanitize a value recursively to ensure valid UTF-8.
     * Strips control characters and replaces invalid UTF-8 byte sequences.
     */
    private function sanitizeUtf8($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitizeUtf8'], $value);
        }

        if (!is_string($value)) {
            return $value;
        }

        // Force UTF-8 encoding
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');

        // Remove control characters except \t (9), \n (10), \r (13)
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

        return $value;
    }
}

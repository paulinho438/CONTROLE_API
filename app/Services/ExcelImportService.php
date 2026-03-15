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
        $dateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d H:i:s') . "')");
        
        $batchId = DB::table('import_batches')->insertGetId([
            'filename' => $originalName,
            'status' => 'processing',
            'created_at' => $dateRaw,
            'updated_at' => $dateRaw
        ]);

        $batch = ImportBatch::find($batchId);

        try {
            $masterImport = new SystemMasterImport($batch->id);
            
            // Excel import in memory/chunks (handles sheets mapping)
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

                // Adiciona os erros em lote para a tabela import_errors
                $errorData = [];
                $chunkDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d H:i:s') . "')");
                
                foreach ($results['errors'] as $error) {
                    $errorData[] = [
                        'import_batch_id' => $batch->id,
                        'sheet_name' => $sheetName,
                        'row_number' => $error['row_number'],
                        'error_message' => $error['error_message'],
                        'row_data' => json_encode($error['row_data'], JSON_UNESCAPED_UNICODE),
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

            $updateDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d H:i:s') . "')");
            DB::table('import_batches')->where('id', $batch->id)->update([
                'status' => 'completed',
                'total_rows' => $totalInserted + $totalUpdated + $totalIgnored + $totalErrors,
                'inserted_rows' => $totalInserted,
                'updated_rows' => $totalUpdated,
                'ignored_rows' => $totalIgnored,
                'error_rows' => $totalErrors,
                'summary' => json_encode($summary, JSON_UNESCAPED_UNICODE),
                'updated_at' => $updateDateRaw
            ]);

            return ImportBatch::find($batch->id);

        } catch (Exception $e) {
            $errorDateRaw = DB::raw("CONVERT(DATETIME2, '" . now()->format('Y-m-d H:i:s') . "')");
            DB::table('import_batches')->where('id', $batch->id)->update([
                'status' => 'failed',
                'summary' => json_encode(['exception' => $e->getMessage()], JSON_UNESCAPED_UNICODE),
                'updated_at' => $errorDateRaw
            ]);

            throw $e;
        }
    }
}

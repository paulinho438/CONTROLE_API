<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExcelImportService;
use App\Models\ImportBatch;
use App\Models\ImportError;
use Exception;

class ExcelImportController extends Controller
{
    protected $importService;

    public function __construct(ExcelImportService $importService)
    {
        $this->importService = $importService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $batch = $this->importService->import($request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Importação concluída.',
                'batch' => $batch
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao processar o arquivo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBatchErrors($batchId)
    {
        $errors = ImportError::where('import_batch_id', $batchId)->orderBy('sheet_name')->orderBy('row_number')->get();
        return response()->json($errors);
    }
}

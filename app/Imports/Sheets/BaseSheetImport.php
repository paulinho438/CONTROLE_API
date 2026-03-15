<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

abstract class BaseSheetImport implements ToCollection, WithHeadingRow
{
    protected $inserted = 0;
    protected $updated = 0;
    protected $ignored = 0;
    protected $errors = [];
    protected $batchId;

    public function __construct($batchId)
    {
        $this->batchId = $batchId;
    }

    public function getResults()
    {
        return [
            'inserted' => $this->inserted,
            'updated' => $this->updated,
            'ignored' => $this->ignored,
            'errors' => $this->errors
        ];
    }

    protected function addError($rowNumber, $message, $rowData)
    {
        $this->errors[] = [
            'row_number' => $rowNumber,
            'error_message' => $message,
            'row_data' => $rowData
        ];
    }

    protected function normalizeString($string)
    {
        if (is_null($string)) {
            return null;
        }
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    protected function normalizeDate($value)
    {
        if (!$value) {
            return null;
        }

        // Se for data em formato numérico do Excel
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Tenta fazer o parse via Carbon
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

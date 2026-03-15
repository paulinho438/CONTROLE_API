<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\Patio;
use Exception;
use Illuminate\Support\Facades\DB;

class PatiosImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nome = $this->normalizeString($row['patio'] ?? null);
            if (empty($nome)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Nome do pátio está vazio.', $row->toArray());
                }
                continue;
            }

            try {
                DB::beginTransaction();

                $patio = Patio::where('nome', $nome)->first();

                if ($patio) {
                    $this->ignored++;
                } else {
                    Patio::create(['nome' => $nome]);
                    $this->inserted++;
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $this->addError($rowNumber, 'Erro ao salvar pátio: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

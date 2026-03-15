<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\UnidadeMedida;
use Exception;
use Illuminate\Support\Facades\DB;

class UnidadesMedidaImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $unidade = $this->normalizeString($row['unidade'] ?? null);
            if (empty($unidade)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Unidade está vazia.', $row->toArray());
                }
                continue;
            }

            try {
                $unidadeObj = UnidadeMedida::where('unidade', $unidade)->first();

                if ($unidadeObj) {
                    $this->ignored++;
                } else {
                    UnidadeMedida::create(['unidade' => $unidade]);
                    $this->inserted++;
                }
            } catch (Exception $e) {
                $this->addError($rowNumber, 'Erro ao salvar unidade de medida: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

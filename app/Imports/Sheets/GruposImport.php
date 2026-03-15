<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\Grupo;
use Exception;
use Illuminate\Support\Facades\DB;

class GruposImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nome = $this->normalizeString($row['grupo'] ?? null);
            if (empty($nome)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Nome do grupo está vazio.', $row->toArray());
                }
                continue;
            }

            $dataCadastro = $this->normalizeDate($row['data_cadastro'] ?? null);

            try {
                $grupo = Grupo::where('nome', $nome)->first();

                if ($grupo) {
                    if ($dataCadastro) {
                        $grupo->update(['data_cadastro' => $dataCadastro]);
                    }
                    $this->updated++;
                } else {
                    Grupo::create([
                        'nome' => $nome,
                        'data_cadastro' => $dataCadastro
                    ]);
                    $this->inserted++;
                }
            } catch (Exception $e) {
                $this->addError($rowNumber, 'Erro ao salvar grupo: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\Colaborador;
use Exception;
use Illuminate\Support\Facades\DB;

class ColaboradoresImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $nome = $this->normalizeString($row['nome'] ?? null);
            if (empty($nome)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Nome do colaborador está vazio.', $row->toArray());
                }
                continue;
            }

            try {
                DB::beginTransaction();

                $colaborador = Colaborador::where('nome_completo', $nome)->first();

                $data = [
                    'funcao' => $this->normalizeString($row['funcao'] ?? null),
                    'departamento' => $this->normalizeString($row['departamento'] ?? null),
                    'telefone' => $this->normalizeString($row['telefone'] ?? null),
                ];

                if ($colaborador) {
                    $colaborador->update($data);
                    $this->updated++;
                } else {
                    $data['nome_completo'] = $nome;
                    Colaborador::create($data);
                    $this->inserted++;
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $this->addError($rowNumber, 'Erro ao salvar colaborador: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

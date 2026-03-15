<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\Fornecedor;
use Exception;
use Illuminate\Support\Facades\DB;

class FornecedoresImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $razaoSocial = $this->normalizeString($row['razao_social'] ?? null);
            $cnpjCpfOrig = $this->normalizeString($row['cnpj_cpf'] ?? null);

            if (empty($razaoSocial) && empty($cnpjCpfOrig)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Razão social e CNPJ/CPF estão vazios.', $row->toArray());
                }
                continue;
            }

            try {
                $fornecedor = null;
                
                if (!empty($cnpjCpfOrig)) {
                    // Match exato
                    $fornecedor = Fornecedor::where('cnpj_cpf', $cnpjCpfOrig)->first();
                    
                    if (!$fornecedor) {
                        // Tenta remover pontuações comuns
                        $cleanCnpj = preg_replace('/[^0-9]/', '', $cnpjCpfOrig);
                        if (!empty($cleanCnpj)) {
                            // Compatibilidade SQL Server / MySQL fallback
                            $fornecedor = Fornecedor::where(DB::raw("REPLACE(REPLACE(REPLACE(cnpj_cpf, '.', ''), '-', ''), '/', '')"), $cleanCnpj)->first();
                        }
                    }
                }

                if (!$fornecedor && !empty($razaoSocial)) {
                    $fornecedor = Fornecedor::where('razao_social', $razaoSocial)->first();
                }

                $data = [
                    'cidade' => $this->normalizeString($row['cidade'] ?? null),
                    'telefone' => $this->normalizeString($row['telefone'] ?? null),
                ];

                if ($fornecedor) {
                    $fornecedor->update($data);
                    $this->updated++;
                } else {
                    $data['razao_social'] = $razaoSocial;
                    $data['cnpj_cpf'] = $cnpjCpfOrig;
                    Fornecedor::create($data);
                    $this->inserted++;
                }
            } catch (Exception $e) {
                $this->addError($rowNumber, 'Erro ao salvar fornecedor: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

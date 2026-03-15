<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\NotaFiscal;
use App\Models\Fornecedor;
use Exception;
use Illuminate\Support\Facades\DB;

class NotasFiscaisImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $numeroNota = $this->normalizeString($row['numero'] ?? null);
            $fornecedorOrig = $this->normalizeString($row['fornecedor'] ?? null);
            $cnpjCpfOrig = $this->normalizeString($row['cnpj_cpf'] ?? null);

            if (empty($numeroNota)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Número da nota está vazio.', $row->toArray());
                }
                continue;
            }

            $fornecedorId = null;

            if (!empty($cnpjCpfOrig)) {
                $forn = Fornecedor::where('cnpj_cpf', $cnpjCpfOrig)->first();
                if (!$forn) {
                    $cleanCnpj = preg_replace('/[^0-9]/', '', $cnpjCpfOrig);
                    if (!empty($cleanCnpj)) {
                        $forn = Fornecedor::where(DB::raw("REPLACE(REPLACE(REPLACE(cnpj_cpf, '.', ''), '-', ''), '/', '')"), $cleanCnpj)->first();
                    }
                }
                if ($forn) {
                    $fornecedorId = $forn->id;
                }
            }

            if (!$fornecedorId && !empty($fornecedorOrig)) {
                $forn = Fornecedor::where('razao_social', $fornecedorOrig)->first();
                if ($forn) {
                    $fornecedorId = $forn->id;
                }
            }

            if (!$fornecedorId) {
                $this->addError($rowNumber, 'Inconsistência: Fornecedor não encontrado (por CNPJ nem Razão Social). A nota não terá vínculo ao cadastro de fornecedor.', $row->toArray());
            }

            try {
                $nota = NotaFiscal::where('numero_nota', $numeroNota)->first();

                $data = [
                    'fornecedor_id' => $fornecedorId,
                    'razao_social' => $fornecedorOrig,
                    'cnpj_cpf' => $cnpjCpfOrig,
                    'data_emissao' => $this->normalizeDate($row['data_emissao'] ?? null),
                    'data_recebimento' => $this->normalizeDate($row['data_recebimento'] ?? null),
                    'peso_nota' => isset($row['peso']) && $row['peso'] !== '' ? floatval($row['peso']) : null,
                    'valor' => isset($row['valor']) && $row['valor'] !== '' ? floatval($row['valor']) : null,
                ];

                if ($nota) {
                    $nota->update($data);
                    $this->updated++;
                } else {
                    $data['numero_nota'] = $numeroNota;
                    NotaFiscal::create($data);
                    $this->inserted++;
                }
            } catch (Exception $e) {
                $this->addError($rowNumber, 'Erro ao salvar nota fiscal: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

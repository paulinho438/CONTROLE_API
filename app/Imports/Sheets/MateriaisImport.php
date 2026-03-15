<?php

namespace App\Imports\Sheets;

use Illuminate\Support\Collection;
use App\Models\Material;
use App\Models\Grupo;
use Exception;
use Illuminate\Support\Facades\DB;

class MateriaisImport extends BaseSheetImport
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            
            $nomeMaterial = $this->normalizeString($row['material'] ?? null);
            if (!empty($nomeMaterial)) {
                $nomeMaterial = substr($nomeMaterial, 0, 255);
            }

            if (empty($nomeMaterial)) {
                if (!empty(array_filter($row->toArray()))) {
                    $this->addError($rowNumber, 'Nome do material está vazio.', $row->toArray());
                }
                continue;
            }

            $nomeGrupo = $this->normalizeString($row['grupo'] ?? null);
            $grupoId = null;

            if (!empty($nomeGrupo)) {
                $grupo = Grupo::where('nome', $nomeGrupo)->first();
                if ($grupo) {
                    $grupoId = $grupo->id;
                } else {
                    $this->addError($rowNumber, "Inconsistência: Grupo '{$nomeGrupo}' não encontrado. O material ficará sem grupo.", $row->toArray());
                }
            }

            try {
                $material = Material::where('nome', $nomeMaterial)->first();
                
                $aplicacao = $this->normalizeString($row['aplicacao'] ?? null);
                if (!empty($aplicacao)) $aplicacao = substr($aplicacao, 0, 255);
                
                $corPredominante = $this->normalizeString($row['cor_predominante'] ?? null);
                if (!empty($corPredominante)) $corPredominante = substr($corPredominante, 0, 255);
                
                $densidade = $this->normalizeString($row['densidade_kmm'] ?? null);
                if (!empty($densidade)) $densidade = substr($densidade, 0, 255);

                $data = [
                    'aplicacao' => $aplicacao,
                    'cor_predominante' => $corPredominante,
                    'comprimento_m' => isset($row['comprimento_m']) && $row['comprimento_m'] !== '' ? floatval($row['comprimento_m']) : null,
                    'largura_m' => isset($row['largura_m']) && $row['largura_m'] !== '' ? floatval($row['largura_m']) : null,
                    'altura_m' => isset($row['altura_m']) && $row['altura_m'] !== '' ? floatval($row['altura_m']) : null,
                    'massa_kg' => isset($row['massa_kg']) && $row['massa_kg'] !== '' ? floatval($row['massa_kg']) : null,
                    'densidade_kmm' => $densidade,
                    'estoque_previsto' => isset($row['estoque_previsto']) && $row['estoque_previsto'] !== '' ? floatval($row['estoque_previsto']) : null,
                ];
                
                if ($grupoId) {
                    $data['grupo_id'] = $grupoId;
                }

                if ($material) {
                    $material->update($data);
                    $this->updated++;
                } else {
                    $data['nome'] = $nomeMaterial;
                    Material::create($data);
                    $this->inserted++;
                }
            } catch (Exception $e) {
                $this->addError($rowNumber, 'Erro ao salvar material: ' . $e->getMessage(), $row->toArray());
            }
        }
    }
}

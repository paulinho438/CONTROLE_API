<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\Sheets\GruposImport;
use App\Imports\Sheets\MateriaisImport;
use App\Imports\Sheets\PatiosImport;
use App\Imports\Sheets\FornecedoresImport;
use App\Imports\Sheets\UnidadesMedidaImport;
use App\Imports\Sheets\ColaboradoresImport;
use App\Imports\Sheets\NotasFiscaisImport;

class SystemMasterImport implements WithMultipleSheets
{
    private $batchId;
    public $sheets = [];

    public function __construct($batchId)
    {
        $this->batchId = $batchId;
        $this->sheets = [
            'Grupos' => new GruposImport($this->batchId),
            'Materiais' => new MateriaisImport($this->batchId),
            'Pátios' => new PatiosImport($this->batchId),
            'Fornecedores' => new FornecedoresImport($this->batchId),
            'Unidades de Medida' => new UnidadesMedidaImport($this->batchId),
            'Colaboradores' => new ColaboradoresImport($this->batchId),
            'Notas Fiscais' => new NotasFiscaisImport($this->batchId),
        ];
    }

    public function sheets(): array
    {
        // Se a aba existir no excel, o Maatwebsite irá chamar a classe correspondente.
        // Toleramos caso alguma falhe, porém configuramos o WithMultipleSheets
        return $this->sheets;
    }
}

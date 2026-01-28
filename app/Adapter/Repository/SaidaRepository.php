<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\SaidaRepositoryInterface;
use App\Models\Saida;

class SaidaRepository extends BaseEloquentRepository implements SaidaRepositoryInterface
{
    public function __construct(Saida $model)
    {
        parent::__construct($model);
    }

    public function buscarPorRomaneio(string $numeroRomaneio)
    {
        return $this->model->newQuery()
            ->where('numero_romaneio', $numeroRomaneio)
            ->with(['material', 'patio', 'destinoPatio', 'unidadeMedida', 'responsavel', 'grupo'])
            ->orderBy('id', 'asc')
            ->get();
    }
}


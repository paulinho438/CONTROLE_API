<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\UnidadeMedidaRepositoryInterface;
use App\Models\UnidadeMedida;

class UnidadeMedidaRepository extends BaseEloquentRepository implements UnidadeMedidaRepositoryInterface
{
    public function __construct(UnidadeMedida $model)
    {
        parent::__construct($model);
    }
}


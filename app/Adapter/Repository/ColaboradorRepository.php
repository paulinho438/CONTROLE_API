<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\ColaboradorRepositoryInterface;
use App\Models\Colaborador;

class ColaboradorRepository extends BaseEloquentRepository implements ColaboradorRepositoryInterface
{
    public function __construct(Colaborador $model)
    {
        parent::__construct($model);
    }
}


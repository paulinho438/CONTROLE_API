<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\EntradaRepositoryInterface;
use App\Models\Entrada;

class EntradaRepository extends BaseEloquentRepository implements EntradaRepositoryInterface
{
    public function __construct(Entrada $model)
    {
        parent::__construct($model);
    }
}


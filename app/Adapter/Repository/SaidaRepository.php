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
}


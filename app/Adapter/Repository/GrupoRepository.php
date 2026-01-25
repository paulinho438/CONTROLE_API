<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\GrupoRepositoryInterface;
use App\Models\Grupo;

class GrupoRepository extends BaseEloquentRepository implements GrupoRepositoryInterface
{
    public function __construct(Grupo $model)
    {
        parent::__construct($model);
    }
}


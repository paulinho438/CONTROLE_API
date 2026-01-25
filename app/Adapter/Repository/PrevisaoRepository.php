<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\PrevisaoRepositoryInterface;
use App\Models\Previsao;

class PrevisaoRepository extends BaseEloquentRepository implements PrevisaoRepositoryInterface
{
    public function __construct(Previsao $model)
    {
        parent::__construct($model);
    }
}


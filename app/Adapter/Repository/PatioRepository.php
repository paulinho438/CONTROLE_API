<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\PatioRepositoryInterface;
use App\Models\Patio;

class PatioRepository extends BaseEloquentRepository implements PatioRepositoryInterface
{
    public function __construct(Patio $model)
    {
        parent::__construct($model);
    }
}


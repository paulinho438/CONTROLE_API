<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\MaterialRepositoryInterface;
use App\Models\Material;

class MaterialRepository extends BaseEloquentRepository implements MaterialRepositoryInterface
{
    public function __construct(Material $model)
    {
        parent::__construct($model);
    }
}


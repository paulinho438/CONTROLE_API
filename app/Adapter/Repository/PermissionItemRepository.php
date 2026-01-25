<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\PermissionItemRepositoryInterface;
use App\Models\Permitem;

class PermissionItemRepository extends BaseEloquentRepository implements PermissionItemRepositoryInterface
{
    public function __construct(Permitem $model)
    {
        parent::__construct($model);
    }
}


<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\PermissionGroupRepositoryInterface;
use App\Models\Permgroup;

class PermissionGroupRepository extends BaseEloquentRepository implements PermissionGroupRepositoryInterface
{
    public function __construct(Permgroup $model)
    {
        parent::__construct($model);
    }

    public function all(array $filters = [])
    {
        return $this->model->newQuery()->with('items')->orderBy('id', 'desc')->get();
    }

    public function find(int $id)
    {
        return $this->model->newQuery()->with('items')->find($id);
    }

    public function syncItems(int $id, array $permitemIds): void
    {
        $group = $this->model->find($id);
        if ($group) {
            $group->items()->sync($permitemIds);
        }
    }
}


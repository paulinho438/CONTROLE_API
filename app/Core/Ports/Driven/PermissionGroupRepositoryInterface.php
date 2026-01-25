<?php

namespace App\Core\Ports\Driven;

interface PermissionGroupRepositoryInterface extends CrudRepositoryInterface
{
    public function syncItems(int $id, array $permitemIds): void;
}


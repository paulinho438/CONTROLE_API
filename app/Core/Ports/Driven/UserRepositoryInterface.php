<?php

namespace App\Core\Ports\Driven;

interface UserRepositoryInterface extends CrudRepositoryInterface
{
    public function syncGroups(int $userId, array $groupIds): void;
}

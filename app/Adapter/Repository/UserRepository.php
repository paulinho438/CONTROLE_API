<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function syncGroups(int $userId, array $groupIds): void
    {
        $user = $this->model->find($userId);
        if ($user) {
            $user->groups()->sync($groupIds);
        }
    }
}

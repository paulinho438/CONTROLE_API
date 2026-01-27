<?php

namespace App\Services;

use App\Core\Ports\Driven\UserRepositoryInterface;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listar(array $filters = [])
    {
        return $this->repository->all($filters)->load('groups');
    }

    public function criar(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $groups = $data['groups'] ?? [];
        unset($data['groups']);

        $user = $this->repository->create($data);
        
        if (!empty($groups)) {
            $this->repository->syncGroups($user->id, $groups);
        }

        return $user->load('groups');
    }

    public function atualizar(int $id, array $data)
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new BusinessException('Usuário não encontrado.');
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $groups = $data['groups'] ?? null;
        unset($data['groups']);

        $user = $this->repository->update($id, $data);
        
        if ($groups !== null) {
            $this->repository->syncGroups($user->id, $groups);
        }

        return $user->load('groups');
    }

    public function excluir(int $id): void
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new BusinessException('Usuário não encontrado.');
        }

        $this->repository->delete($id);
    }

    public function obter(int $id)
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new BusinessException('Usuário não encontrado.');
        }

        return $user->load('groups');
    }
}

<?php

namespace App\Services;

use App\Core\Ports\Driven\PatioRepositoryInterface;
use App\Exceptions\BusinessException;

class PatioService
{
    private PatioRepositoryInterface $repository;

    public function __construct(PatioRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listar(array $filters = [])
    {
        return $this->repository->all($filters);
    }

    public function criar(array $data)
    {
        return $this->repository->create($data);
    }

    public function atualizar(int $id, array $data)
    {
        $patio = $this->repository->update($id, $data);
        if (!$patio) {
            throw new BusinessException('Pátio não encontrado.');
        }

        return $patio;
    }

    public function excluir(int $id): void
    {
        $patio = $this->repository->find($id);
        if (!$patio) {
            throw new BusinessException('Pátio não encontrado.');
        }

        $this->repository->delete($id);
    }
}


<?php

namespace App\Services;

use App\Core\Ports\Driven\MaterialRepositoryInterface;
use App\Exceptions\BusinessException;

class MaterialService
{
    private MaterialRepositoryInterface $repository;

    public function __construct(MaterialRepositoryInterface $repository)
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
        $material = $this->repository->update($id, $data);
        if (!$material) {
            throw new BusinessException('Material não encontrado.');
        }

        return $material;
    }

    public function excluir(int $id): void
    {
        $material = $this->repository->find($id);
        if (!$material) {
            throw new BusinessException('Material não encontrado.');
        }

        $this->repository->delete($id);
    }
}


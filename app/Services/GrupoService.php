<?php

namespace App\Services;

use App\Core\Ports\Driven\GrupoRepositoryInterface;
use App\Exceptions\BusinessException;

class GrupoService
{
    private GrupoRepositoryInterface $repository;

    public function __construct(GrupoRepositoryInterface $repository)
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
        $grupo = $this->repository->update($id, $data);
        if (!$grupo) {
            throw new BusinessException('Grupo não encontrado.');
        }

        return $grupo;
    }

    public function excluir(int $id): void
    {
        $grupo = $this->repository->find($id);
        if (!$grupo) {
            throw new BusinessException('Grupo não encontrado.');
        }

        $this->repository->delete($id);
    }
}


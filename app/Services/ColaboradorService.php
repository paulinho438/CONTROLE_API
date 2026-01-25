<?php

namespace App\Services;

use App\Core\Ports\Driven\ColaboradorRepositoryInterface;
use App\Exceptions\BusinessException;

class ColaboradorService
{
    private ColaboradorRepositoryInterface $repository;

    public function __construct(ColaboradorRepositoryInterface $repository)
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
        $colaborador = $this->repository->update($id, $data);
        if (!$colaborador) {
            throw new BusinessException('Colaborador não encontrado.');
        }

        return $colaborador;
    }

    public function excluir(int $id): void
    {
        $colaborador = $this->repository->find($id);
        if (!$colaborador) {
            throw new BusinessException('Colaborador não encontrado.');
        }

        $this->repository->delete($id);
    }
}


<?php

namespace App\Services;

use App\Core\Ports\Driven\EntradaRepositoryInterface;
use App\Exceptions\BusinessException;

class EntradaService
{
    private EntradaRepositoryInterface $repository;

    public function __construct(EntradaRepositoryInterface $repository)
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
        $entrada = $this->repository->update($id, $data);
        if (!$entrada) {
            throw new BusinessException('Entrada não encontrada.');
        }

        return $entrada;
    }

    public function excluir(int $id): void
    {
        $entrada = $this->repository->find($id);
        if (!$entrada) {
            throw new BusinessException('Entrada não encontrada.');
        }

        $this->repository->delete($id);
    }
}


<?php

namespace App\Services;

use App\Core\Ports\Driven\UnidadeMedidaRepositoryInterface;
use App\Exceptions\BusinessException;

class UnidadeMedidaService
{
    private UnidadeMedidaRepositoryInterface $repository;

    public function __construct(UnidadeMedidaRepositoryInterface $repository)
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
        $unidade = $this->repository->update($id, $data);
        if (!$unidade) {
            throw new BusinessException('Unidade de medida não encontrada.');
        }

        return $unidade;
    }

    public function excluir(int $id): void
    {
        $unidade = $this->repository->find($id);
        if (!$unidade) {
            throw new BusinessException('Unidade de medida não encontrada.');
        }

        $this->repository->delete($id);
    }
}


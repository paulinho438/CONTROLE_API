<?php

namespace App\Services;

use App\Core\Ports\Driven\FornecedorRepositoryInterface;
use App\Exceptions\BusinessException;

class FornecedorService
{
    private FornecedorRepositoryInterface $repository;

    public function __construct(FornecedorRepositoryInterface $repository)
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
        $fornecedor = $this->repository->update($id, $data);
        if (!$fornecedor) {
            throw new BusinessException('Fornecedor não encontrado.');
        }

        return $fornecedor;
    }

    public function excluir(int $id): void
    {
        $fornecedor = $this->repository->find($id);
        if (!$fornecedor) {
            throw new BusinessException('Fornecedor não encontrado.');
        }

        $this->repository->delete($id);
    }
}


<?php

namespace App\Services;

use App\Core\Ports\Driven\PrevisaoRepositoryInterface;
use App\Exceptions\BusinessException;

class PrevisaoService
{
    private PrevisaoRepositoryInterface $repository;

    public function __construct(PrevisaoRepositoryInterface $repository)
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
        $previsao = $this->repository->update($id, $data);
        if (!$previsao) {
            throw new BusinessException('Previsão não encontrada.');
        }

        return $previsao;
    }

    public function excluir(int $id): void
    {
        $previsao = $this->repository->find($id);
        if (!$previsao) {
            throw new BusinessException('Previsão não encontrada.');
        }

        $this->repository->delete($id);
    }
}


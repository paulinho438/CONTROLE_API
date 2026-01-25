<?php

namespace App\Services;

use App\Core\Ports\Driven\NotaFiscalRepositoryInterface;
use App\Exceptions\BusinessException;

class NotaFiscalService
{
    private NotaFiscalRepositoryInterface $repository;

    public function __construct(NotaFiscalRepositoryInterface $repository)
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
        $nota = $this->repository->update($id, $data);
        if (!$nota) {
            throw new BusinessException('Nota fiscal não encontrada.');
        }

        return $nota;
    }

    public function excluir(int $id): void
    {
        $nota = $this->repository->find($id);
        if (!$nota) {
            throw new BusinessException('Nota fiscal não encontrada.');
        }

        $this->repository->delete($id);
    }
}


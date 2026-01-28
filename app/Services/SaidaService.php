<?php

namespace App\Services;

use App\Core\Ports\Driven\SaidaRepositoryInterface;
use App\Exceptions\BusinessException;

class SaidaService
{
    private SaidaRepositoryInterface $repository;

    public function __construct(SaidaRepositoryInterface $repository)
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
        $saida = $this->repository->update($id, $data);
        if (!$saida) {
            throw new BusinessException('Saída não encontrada.');
        }

        return $saida;
    }

    public function excluir(int $id): void
    {
        $saida = $this->repository->find($id);
        if (!$saida) {
            throw new BusinessException('Saída não encontrada.');
        }

        $this->repository->delete($id);
    }

    public function buscarRomaneio(string $numeroRomaneio)
    {
        $saidas = $this->repository->buscarPorRomaneio($numeroRomaneio);
        if ($saidas->isEmpty()) {
            throw new BusinessException('Romaneio não encontrado.');
        }
        return $saidas;
    }
}


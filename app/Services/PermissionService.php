<?php

namespace App\Services;

use App\Core\Ports\Driven\PermissionGroupRepositoryInterface;
use App\Core\Ports\Driven\PermissionItemRepositoryInterface;
use App\Exceptions\BusinessException;

class PermissionService
{
    private PermissionGroupRepositoryInterface $groupRepository;
    private PermissionItemRepositoryInterface $itemRepository;

    public function __construct(
        PermissionGroupRepositoryInterface $groupRepository,
        PermissionItemRepositoryInterface $itemRepository
    ) {
        $this->groupRepository = $groupRepository;
        $this->itemRepository = $itemRepository;
    }

    public function listarGrupos()
    {
        return $this->groupRepository->all();
    }

    public function obterGrupo(int $id)
    {
        return $this->groupRepository->find($id);
    }

    public function listarPermissoes()
    {
        return $this->itemRepository->all();
    }

    public function criarGrupo(array $data)
    {
        $items = $data['permitems'] ?? [];
        unset($data['permitems']);

        $group = $this->groupRepository->create($data);
        $this->groupRepository->syncItems($group->id, $items);

        return $group;
    }

    public function atualizarGrupo(int $id, array $data)
    {
        $items = $data['permitems'] ?? [];
        unset($data['permitems']);

        $group = $this->groupRepository->update($id, $data);
        if (!$group) {
            throw new BusinessException('Grupo de permissão não encontrado.');
        }

        $this->groupRepository->syncItems($id, $items);

        return $group;
    }

    public function excluirGrupo(int $id): void
    {
        $group = $this->groupRepository->find($id);
        if (!$group) {
            throw new BusinessException('Grupo de permissão não encontrado.');
        }

        $this->groupRepository->delete($id);
    }
}


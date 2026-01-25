<?php

namespace App\Core\Ports\Driven;

interface DashboardRepositoryInterface
{
    public function resumoGeral(): array;
    public function resumoEstoque(array $grupoIds, array $patioIds): array;
}


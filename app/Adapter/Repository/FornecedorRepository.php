<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\FornecedorRepositoryInterface;
use App\Models\Fornecedor;

class FornecedorRepository extends BaseEloquentRepository implements FornecedorRepositoryInterface
{
    public function __construct(Fornecedor $model)
    {
        parent::__construct($model);
    }
}


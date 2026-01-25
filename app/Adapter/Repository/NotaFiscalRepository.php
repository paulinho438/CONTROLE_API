<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\NotaFiscalRepositoryInterface;
use App\Models\NotaFiscal;

class NotaFiscalRepository extends BaseEloquentRepository implements NotaFiscalRepositoryInterface
{
    public function __construct(NotaFiscal $model)
    {
        parent::__construct($model);
    }
}


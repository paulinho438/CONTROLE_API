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

    public function all(array $filters = [])
    {
        $query = $this->model->newQuery();

        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if ($field === 'numero_nota') {
                $query->where('numero_nota', 'LIKE', '%' . $value . '%');
                continue;
            }
            $query->where($field, $value);
        }

        return $query->orderBy('id', 'desc')->get();
    }
}


<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentRepository implements CrudRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = [])
    {
        $query = $this->model->newQuery();

        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $query->where($field, $value);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        // Para SQL Server, usa inserção direta com DB::raw para campos de data
        $connection = \Illuminate\Support\Facades\DB::connection();
        $driver = $connection->getDriverName();
        
        if ($driver === 'sqlsrv') {
            return $this->createWithSqlServerDates($data);
        }
        
        return $this->model->create($data);
    }
    
    /**
     * Cria registro com formatação específica para SQL Server
     */
    protected function createWithSqlServerDates(array $data)
    {
        $table = $this->model->getTable();
        $now = now();
        
        // Prepara dados para inserção
        $insertData = [];
        $dateFields = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'data_cadastro' && $value !== null) {
                // Formata data_cadastro como DATE
                $dateStr = $this->formatDateForSqlServer($value);
                if ($dateStr) {
                    $dateFields[$key] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATE, '{$dateStr}')");
                } else {
                    $insertData[$key] = $value;
                }
            } elseif (($key === 'created_at' || $key === 'updated_at') && $value !== null) {
                // Formata timestamps como DATETIME2
                $dateTimeStr = $this->formatDateTimeForSqlServer($value);
                if ($dateTimeStr) {
                    $dateFields[$key] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATETIME2, '{$dateTimeStr}')");
                } else {
                    $insertData[$key] = $value;
                }
            } else {
                $insertData[$key] = $value;
            }
        }
        
        // Se não tem created_at/updated_at, adiciona
        if (!isset($dateFields['created_at']) && !isset($insertData['created_at'])) {
            $dateTimeStr = $now->format('Y-m-d H:i:s');
            $dateFields['created_at'] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATETIME2, '{$dateTimeStr}')");
        }
        if (!isset($dateFields['updated_at']) && !isset($insertData['updated_at'])) {
            $dateTimeStr = $now->format('Y-m-d H:i:s');
            $dateFields['updated_at'] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATETIME2, '{$dateTimeStr}')");
        }
        
        // Combina dados normais com campos de data
        $finalData = array_merge($insertData, $dateFields);
        
        // Insere usando query builder
        $id = \Illuminate\Support\Facades\DB::table($table)->insertGetId($finalData);
        
        // Retorna o modelo recém-criado
        return $this->model->find($id);
    }
    
    /**
     * Formata data para SQL Server DATE
     */
    protected function formatDateForSqlServer($value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        try {
            if ($value instanceof \Carbon\Carbon) {
                return $value->format('Y-m-d');
            } elseif (is_string($value)) {
                $cleanValue = preg_replace('/T.*$/', '', $value);
                $cleanValue = preg_replace('/\s+\d{2}:\d{2}:\d{2}.*$/', '', $cleanValue);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $cleanValue)) {
                    return $cleanValue;
                }
                $parsed = \Carbon\Carbon::parse($cleanValue);
                return $parsed->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }
    
    /**
     * Formata datetime para SQL Server DATETIME2
     */
    protected function formatDateTimeForSqlServer($value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        try {
            if ($value instanceof \Carbon\Carbon) {
                return $value->format('Y-m-d H:i:s');
            } elseif (is_string($value)) {
                $parsed = \Carbon\Carbon::parse($value);
                return $parsed->format('Y-m-d H:i:s');
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }

    public function update(int $id, array $data)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return null;
        }
        
        // Para SQL Server, formata campos de data antes de atualizar
        $connection = \Illuminate\Support\Facades\DB::connection();
        $driver = $connection->getDriverName();
        
        if ($driver === 'sqlsrv') {
            return $this->updateWithSqlServerDates($id, $data);
        }
        
        $item->fill($data);
        $item->save();

        return $item;
    }
    
    /**
     * Atualiza registro com formatação específica para SQL Server
     */
    protected function updateWithSqlServerDates(int $id, array $data)
    {
        $table = $this->model->getTable();
        $now = now();
        
        // Prepara dados para atualização
        $updateData = [];
        $dateFields = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'data_cadastro' && $value !== null) {
                // Formata data_cadastro como DATE
                $dateStr = $this->formatDateForSqlServer($value);
                if ($dateStr) {
                    $dateFields[$key] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATE, '{$dateStr}')");
                } else {
                    $updateData[$key] = $value;
                }
            } elseif ($key === 'updated_at' && $value !== null) {
                // Formata updated_at como DATETIME2
                $dateTimeStr = $this->formatDateTimeForSqlServer($value);
                if ($dateTimeStr) {
                    $dateFields[$key] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATETIME2, '{$dateTimeStr}')");
                } else {
                    $updateData[$key] = $value;
                }
            } else {
                $updateData[$key] = $value;
            }
        }
        
        // Sempre atualiza updated_at se não foi fornecido
        if (!isset($dateFields['updated_at']) && !isset($updateData['updated_at'])) {
            $dateTimeStr = $now->format('Y-m-d H:i:s');
            $dateFields['updated_at'] = \Illuminate\Support\Facades\DB::raw("CONVERT(DATETIME2, '{$dateTimeStr}')");
        }
        
        // Combina dados normais com campos de data
        $finalData = array_merge($updateData, $dateFields);
        
        // Atualiza usando query builder
        \Illuminate\Support\Facades\DB::table($table)
            ->where('id', $id)
            ->update($finalData);
        
        // Retorna o modelo atualizado
        return $this->model->find($id);
    }

    public function delete(int $id): void
    {
        $item = $this->model->find($id);
        if ($item) {
            $item->delete();
        }
    }
}


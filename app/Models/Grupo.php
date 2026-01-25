<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'nome',
        'data_cadastro'
    ];

    /**
     * Mutator para data_cadastro - formata para SQL Server DATE
     */
    public function setDataCadastroAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['data_cadastro'] = null;
            return;
        }
        
        try {
            $date = null;
            
            if ($value instanceof \Carbon\Carbon) {
                $date = $value;
            } elseif (is_string($value)) {
                // Remove timezone e hora se presente (formato ISO)
                $cleanValue = preg_replace('/T.*$/', '', $value);
                $cleanValue = preg_replace('/\s+\d{2}:\d{2}:\d{2}.*$/', '', $cleanValue);
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', $cleanValue);
            } else {
                $this->attributes['data_cadastro'] = $value;
                return;
            }
            
            // Formata como 'Y-m-d' (formato padrão aceito pelo SQL Server)
            $this->attributes['data_cadastro'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Se falhar, tenta extrair apenas a parte da data
            if (is_string($value)) {
                if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $value, $matches)) {
                    $this->attributes['data_cadastro'] = $matches[1];
                } else {
                    // Tenta outro formato comum
                    $this->attributes['data_cadastro'] = $value;
                }
            } else {
                $this->attributes['data_cadastro'] = $value;
            }
        }
    }

    /**
     * Boot do model - eventos para compatibilidade com SQL Server
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Formata timestamps para string Y-m-d H:i:s
            if (!isset($model->attributes['created_at']) || $model->attributes['created_at'] === null) {
                $model->attributes['created_at'] = now()->format('Y-m-d H:i:s');
            } elseif ($model->attributes['created_at'] instanceof \Carbon\Carbon) {
                $model->attributes['created_at'] = $model->attributes['created_at']->format('Y-m-d H:i:s');
            }
            if (!isset($model->attributes['updated_at']) || $model->attributes['updated_at'] === null) {
                $model->attributes['updated_at'] = now()->format('Y-m-d H:i:s');
            } elseif ($model->attributes['updated_at'] instanceof \Carbon\Carbon) {
                $model->attributes['updated_at'] = $model->attributes['updated_at']->format('Y-m-d H:i:s');
            }
        });
        
        static::updating(function ($model) {
            // Formata updated_at para string Y-m-d H:i:s
            if (!isset($model->attributes['updated_at']) || $model->attributes['updated_at'] === null) {
                $model->attributes['updated_at'] = now()->format('Y-m-d H:i:s');
            } elseif ($model->attributes['updated_at'] instanceof \Carbon\Carbon) {
                $model->attributes['updated_at'] = $model->attributes['updated_at']->format('Y-m-d H:i:s');
            }
        });
    }

    public function materiais()
    {
        return $this->hasMany(Material::class, 'grupo_id');
    }
}


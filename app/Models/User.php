<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'password'
    ];

    public $fillable = [
        'login',
        'nome_completo',
        'email',
        'password',
        'status'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Boot do model - eventos para compatibilidade com SQL Server
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
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
            if (!isset($model->attributes['updated_at']) || $model->attributes['updated_at'] === null) {
                $model->attributes['updated_at'] = now()->format('Y-m-d H:i:s');
            } elseif ($model->attributes['updated_at'] instanceof \Carbon\Carbon) {
                $model->attributes['updated_at'] = $model->attributes['updated_at']->format('Y-m-d H:i:s');
            }
        });
    }

    public function groups() {
        return $this->belongsToMany(Permgroup::class);
    }
}


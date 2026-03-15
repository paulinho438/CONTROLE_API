<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    protected $table = 'import_batches';

    protected $fillable = [
        'filename',
        'status',
        'total_rows',
        'inserted_rows',
        'updated_rows',
        'ignored_rows',
        'error_rows',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    public function errors()
    {
        return $this->hasMany(ImportError::class, 'import_batch_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    use HasFactory;

    protected $table = 'import_errors';

    // Disable Eloquent's automatic timestamps so we can insert raw SQL Server DATETIME2 manually
    public $timestamps = false;

    protected $fillable = [
        'import_batch_id',
        'sheet_name',
        'row_number',
        'error_message',
        'row_data',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'row_data' => 'array',
    ];

    public function batch()
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    use HasFactory;

    protected $table = 'import_errors';

    protected $fillable = [
        'import_batch_id',
        'sheet_name',
        'row_number',
        'error_message',
        'row_data'
    ];

    protected $casts = [
        'row_data' => 'array',
    ];

    public function batch()
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_size',
        'source_format',
        'target_format',
        'status',
        'execution_time',
        'error_message',
        'ip_address',
    ];
}

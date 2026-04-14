<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileShare extends Model
{
    protected $fillable = [
        'file_id',
        'accessed_by_ip',
        'accessed_by_agent',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}

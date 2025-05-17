<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = [
        'url',
        'type',
        'size',
        'class'
    ];

    public function isNormal()
    {
        return $this->class == 'normal';
    }
}

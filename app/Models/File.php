<?php

namespace App\Models;

use App\Jobs\Message\ImageProccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    /** @use HasFactory<\Database\Factories\FileFactory> */
    use HasFactory;

    protected $fillable = [
        'url',
        'type',
        'size',
        'class',
        'mime_type',
        'original_name',
        'metadata',
        'user_id',
    ];

    protected function casts(){
        return [
            'size' => 'integer',
            'metadata' => 'array'
        ];
    }

    
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });

        static::created(function ($file) {
            if ($file->isImage()) {
                $file->classifyImage();
            }
        });
    }

    public function message()
    {
        return $this->hasOne(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function IsMine(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function canDelete(User $user)
    {
        return $this->IsMine($user);
    }
    
    public function isImage()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function deletePhysicalFile(): void
    {
        $path = public_path($this->url);

        if (file_exists($path)) {
            unlink($path);
        }

        if (Storage::disk('public')->exists($this->url)) {
            Storage::disk('public')->delete($this->url);
        }
    }

    public function isNormal()
    {
        return $this->class === 'normal';
    }

    public function classifyImage()
    {
        ImageProccess::dispatch($this);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'text',
        'user_id',
        'conversation_id',
        'file_id',
        'read_at',
        'status'
    ];

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
    }

    public function accessToMessage(User $user)
    {
        return $this->conversation->participants->contains($user->id);
    }

    public function IsMine(User $user)
    {
        return $this->sender->id === $user->id;
    }

    public function canDelete(User $user)
    {
        return $this->IsMine($user) || in_array($user->id , $this->conversation->adminsId);
    }
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function forwardedFrom()
    {
        return $this->belongsTo(Message::class, 'forwarded_from_id');
    }

    public function forwards()
    {
        return $this->hasMany(Message::class, 'forwarded_from_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function conversationType()
    {
        return $this->conversation->type;
    }

    public function hasFile()
    {
        return $this->file() && !empty($this->file()->url);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'text',
        'user_id',
        'conversation_id',
        'file_id',
        'is_read'
    ];

    protected $casts = [
        'is_read' => "boolean"
    ];

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

    public function conversationType()
    {
        return $this->conversation->type;
    }

    public function hasFile()
    {
        return $this->file() && !empty($this->file()->url);
    }
}

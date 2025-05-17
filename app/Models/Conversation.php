<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property array $settings
 * @property boolean $status
 * @property \Illuminate\Support\Collection $users
 */
class Conversation extends Model
{
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';
    const TYPE_CHANNEL = 'channel';

    /** @use HasFactory<\Database\Factories\ConversationFactory> */
    use HasFactory , SoftDeletes;

    protected $fillable = ["name","token","type","bio","settings","details","status",'file_id'];

    protected $casts = ['settings' => 'array' , 'status' => 'boolean'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeOfType($query , $type)
    {
        return $query->where('type' , $type);
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWithUser($query, $userId)
    {
        return $query->whereHas('users', fn($q) => $q->where('user_id', $userId));
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}

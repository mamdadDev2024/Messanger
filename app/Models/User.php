<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ["first_name","last_name",'file_id',"user_name","bio","settings","email","password"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'setting' => 'array'
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
    }

    public function messages()
    {
        $this->hasMany(Message::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function privates()
    {
        return $this->conversations()->ofType(Conversation::TYPE_PRIVATE);
    }

    public function channels()
    {
        return $this->conversations()->ofType(Conversation::TYPE_CHANNEL);
    }

    public function groups()
    {
        return $this->conversations()->ofType(Conversation::TYPE_GROUP);
    }

    public function avatar()
    {
        return $this->belongsTo(File::class , 'file_id');
    }
}

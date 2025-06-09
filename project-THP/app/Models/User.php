<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'user_type',
        'status',
        'role_id',
    ];

    protected $hidden = [
       
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // علاقات
    public function formPosts()
    {
        return $this->hasMany(FormPost::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->user_type) {
                switch ($user->user_type) {
                    case 'job_owner':
                        $user->role_id = 1;
                        break;
                    case 'admin':
                        $user->role_id = 2;
                        break;
                    case 'artisan':
                        $user->role_id = 3;
                        break;
                    default:
                        $user->role_id = null; 
                }
            }
        });
    }
}

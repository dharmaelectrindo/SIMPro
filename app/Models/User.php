<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'picture',
        'organization_id',
        'user_crt',
        'user_mdf'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value) {
            return asset('images/users/' . $value);
        } else {
            return asset('images/users/super_avatar.png');
        }
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_crt = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->user_mdf = auth()->id();
            }
        });

        static::deleting(function ($model) {
            // Logika sebelum hapus
        });
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Eloquent;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primary = ['id'];
    protected $fillable = [
        'id',
        'name',
        'email',
        'username',
        'password',
        'picture',
        'organization_id',
        'user_mdf'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    public function getPictureAttribute($value){
        if($value){
            return asset('images/users/'.$value);
        }else{
            return asset('images/users/super_avatar.png');
        }
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class,"user_mdf","id");
    }

    public function template()
    {
        return $this->belongsTo(Template::class,"user_mdf","id");
    }

}

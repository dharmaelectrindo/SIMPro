<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class Organization extends Model
{
    
    use SoftDeletes;

    protected $primary = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'organizations_code',
        'organizations_level',
        'description',
        'user_crt',
        'user_mdf',
    ];
    public function user() {
        return $this->belongsTo(User::class,"user_mdf","id");
    }

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            try {
               
                $model->user_crt = auth()->id();
                $model->user_mdf = auth()->id();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
            
        });

        static::updating(function ($model) {
            $model->user_mdf = auth()->id();
        });

        static::deleting(function ($model) {
            // Logika sebelum hapus
        });
    }

    
}

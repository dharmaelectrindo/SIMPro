<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'user_crt',
        'user_mdf'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->user_crt = auth()->id();
        });

        static::updating(function ($model) {
            $model->user_mdf = auth()->id();
        });

        static::deleting(function ($model) {
            // Logika sebelum hapus
        });
    }
}

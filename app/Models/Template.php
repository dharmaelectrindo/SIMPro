<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $primary = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'description',
        'user_mdf'
    ];

    
}

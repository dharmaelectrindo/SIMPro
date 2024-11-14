<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $primary = ['id'];
    protected $fillable = [
        'department_name',
        'user_id'
    ];


}

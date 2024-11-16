<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
  

class Employee extends Model
{
    use SoftDeletes;

    protected $primary = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'npk',
        'employee_name',
        'email',
        'employee_position',
        'mobile_number',
        'user_mdf'
    ];
    


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
class Organization extends Model
{
    use SoftDeletes;

    protected $primary = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'organizations_code',
        'organizations_level',
        'description',
        'user_mdf'
    ];
    public function user()
        {
            return $this->hasOne(User::class,"id","user_mdf");
        }
    // public function itemDetail(){

    //     //customer_id is a foreign key in customer_items table
   
    //     return $this->hasOne(Customer::class, 'customer_item_id');
   
    //                //A Item will has single detail thats why hasOne relation used here
    //     }
    
}

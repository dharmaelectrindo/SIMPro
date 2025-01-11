<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class Task extends Model
{
    use SoftDeletes;

    protected $primary = ['id'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'parentID', 
        'description', 
        'templateID'
    ];

    public function children()
    {
    return $this->hasMany(Task::class, 'parentID');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parentID');
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

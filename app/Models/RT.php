<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RT extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rts';
    protected $primaryKey = 'id';
    protected $dates = [
        'deleted_at'
    ];
    protected $fillable = [
        'name', 
        'head_name', 
        'head_contact',
    ];

    public function adminRT()
    {
        return $this->hasOne(User::class, 'rts_id')->whereHas('roles', function ($query) {
            $query->where('name', 'admin_rt');
        });
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RT extends Model
{
    use HasFactory;
    protected $table = 'rts';
    protected $fillable = [
        'name', 
        'head_name', 
        'head_contact',
    ];

    public function adminRT()
    {
        return $this->hasOne(User::class, 'rts_id');
    }
    
}

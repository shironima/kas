<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'incomes';
    protected $dates = ['transaction_date'];
    protected $fillable = [
        'category_id', 
        'name', 
        'amount', 
        'description', 
        'transaction_date',
        'rts_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rt()
    {
        return $this->belongsTo(RT::class, 'rts_id');
    }
}

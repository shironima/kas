<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactNotification extends Model
{
    use HasFactory;

    protected $table = 'contact_notification';

    protected $fillable = [ 
        'nama', 
        'no_telepon', 
        'menerima_notif',
    ];

}

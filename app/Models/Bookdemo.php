<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookdemo extends Model
{
    protected $table = 'bookdemo';
    use HasFactory;

    protected $fillable = [
        'identification','fullName', 'mobile',  'email', 'date', 'time', 'isDone', 'isRemote', 'isSeen'
    ];

    

    
}

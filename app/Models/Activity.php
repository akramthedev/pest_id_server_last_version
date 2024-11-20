<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table = 'activity';

    protected $fillable = [
        'user_id', 'action', 'isDanger', "brand", "device_type", "model", "os", "ip_address", "provider"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaque extends Model
{
    protected $table = 'plaques';
    use HasFactory;

    protected $fillable = [
        'farm_id', 'serre_id',  'name' 
    ];

    // A serre belongs to a farm
    public function Serre()
    {
        return $this->belongsTo(Serre::class);
    }

    // A serre can have many predictions
    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }


    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serre extends Model
{
    protected $table = 'serres';
    use HasFactory;

    protected $fillable = [
        'farm_id', 'name',  'size', 'type' 
    ];

    // A serre belongs to a farm
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    // A serre can have many predictions
    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    // A farm can have many greenhouses (serres)
    public function plaques()
    {
        return $this->hasMany(Plaque::class);
    }
    
}

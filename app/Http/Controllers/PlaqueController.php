<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Serre;
use App\Models\User;
use App\Models\Plaque;
use App\Models\Prediction;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaqueController extends Controller
{   


    public function createPlaque(Request $request)
    {

        $plaque = new Plaque();
        $plaque->name = $request->name;
        $plaque->serre_id = $request->idSerre;
        $plaque->farm_id = $request->idFarm;
        $plaque->save();

        return response()->json($plaque, 201);
    } 

}
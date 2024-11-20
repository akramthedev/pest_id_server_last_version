<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Bookdemo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookdemoController extends Controller
{

    public function bookDemoForuser(Request $request)
    {
        $demo = Bookdemo::create([
            'identification' => $request->identification,
            'fullName' => $request->fullName,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'date' => $request->date,
            'time' => $request->time,
            'isRemote' => $request->isRemote,
            'isSeen' => 0
        ]);

        return response()->json($demo, 200);
    }
    


    public function markReservationsAsSeen()
    {
        Bookdemo::query()->update(['isSeen' => 1]);
        return response()->json("Done", 200);
    }




    
    public function getAllDemos()
    {
        $demos = Bookdemo::all();

        return response()->json($demos, 200);
    }

    public function markAsDone($id){

        $demo = Bookdemo::find($id);
        if (!$demo) {
            return response()->json([
                'message' => 'Réservation introuvable.'
            ], 404);
        }

        $demo->isDone = 1;
        $demo->save();

        return response()->json([
            'message' => 'La réservation a été marquée comme finalisée.',
            'demo' => $demo
        ], 200);
        
    }

}
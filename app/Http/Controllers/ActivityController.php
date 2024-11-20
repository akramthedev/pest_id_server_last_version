<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{


    public function createActivity(Request $request, $id)
    {
        $activity = Activity::create([
            'user_id' => $id,
            'action' => $request->action,
            'isDanger' => $request->isDanger
        ]);

        return response()->json($activity, 200);

    }

    public function createActivityByEmail(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $activity = Activity::create([
                'user_id' => $user->id,
                'action' => $request->action,
                'isDanger' => $request->isDanger
            ]);
    
            return response()->json("Done", 200);
        } else {
            return response()->json(['error' => 'User not found.'], 201);
        }
        
    }


    public function createActivityByEmail2(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $activity = Activity::create([
                'user_id' => $user->id,
                'action' => $request->action,
                'isDanger' => $request->isDanger, 
                'brand' => $request->brand,
                'device_type' => $request->device_type,
                'model' => $request->model,
                'os' => $request->os,
                'ip_address' => $request->ip_address,
                'provider' => $request->provider,
            ]);
    
            return response()->json("Done", 200);
        } else {
            return response()->json(['error' => 'User not found.'], 201);
        }
        
    }



  
    public function getAllActivitiesByUser($id)
    {
        $activities = Activity::where('user_id', $id)->get();

        return response()->json($activities, 200);
    }



    
    public function deleteAllActivityByUser($id)
    {
        Activity::where('user_id', $id)->delete();
        return response()->json("Hallo world", 200);
    }

    
}
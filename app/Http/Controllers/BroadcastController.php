<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BroadcastController extends Controller
{
    public function createBroadcast(Request $request)
    {
        $activity = Broadcast::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        User::query()->update(['isNoticeOfBroadcastSeen' => 0]);

        return response()->json($activity, 200);
    }

    public function deleteBroadcast()
    {
        $broadcast = Broadcast::first();

        if ($broadcast) {
            User::query()->update(['isNoticeOfBroadcastSeen' => 1]);
            $broadcast->delete();  
            return response()->json(['message' => 'Broadcast deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Broadcast not found'], 203);
        }
    }

    public function updateBroadcast(Request $request)
    {
        $broadcast = Broadcast::first();

        if ($broadcast) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 203);
            }

            $broadcast->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            User::query()->update(['isNoticeOfBroadcastSeen' => 0]);

            return response()->json($broadcast, 200);
        } else {
            return response()->json(['message' => 'Broadcast not found'], 201);
        }
    }

    public function getBroadcast()
    {
        $broadcast = Broadcast::first();

        
        if ($broadcast) {
            return response()->json($broadcast, 200);
        } else {
            return response()->json(['message' => 'No broadcasts found'], 201);
        }
    }
}
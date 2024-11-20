<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;



class ForgotPass extends Controller
{

    

    public function sendResetLinkEmail(Request $request)
{
    // Validate email
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
    }

    // Generate OTP
    $otp = (new Otp)->generate($request->email, 'numeric', 6, 15);

    // Fetch the user
    $user = User::where('email', $request->email)->first();

    // Update the OTP column in the user's record
    $user->otp = Hash::make($otp->token);
    $user->save();

    // Send OTP via email
    Mail::raw("Your OTP is: {$otp->token}", function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Your OTP for Password Reset');
    });

    return response()->json(['status' => 200, 'message' => 'OTP sent to your email']);
}




    



    // Step 2: Validate OTP
    public function validateOtp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'otp' => 'required',
        ]);



        $user = User::where('email', $request->email)->first();
        
        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json(['message' => 'OTP NE MATCHE PAS'], 287);
        }
        else{
            return response()->json([
                'status' => 200,
                'data' => [
                    'requestOtp' => $request->otp
                ]
            ]); 
        }
        
    }
 
}
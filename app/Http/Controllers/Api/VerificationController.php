<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    
     public function verify(Request $request)
    {
    	$user = $request->user();
    	if ($request->route('id') == $user->getKey() && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }


        return response()->json(['message' => 'Successfully verified']);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified']);
        }

        $request->user()->sendEmailVerificationNotification();
        
        if($request->wantsJson()){
        	return response()->json(['message' => 'Email Sent']);
        }

         return response()->json(['message' => 'The email has been resent']);
    }
}

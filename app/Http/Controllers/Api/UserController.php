<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    
    /*
    *	Register a User
    */
    public function register(Request $request)
        {
        
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);
           
                 	
        	event(new \App\Events\WelcomeUser($user));

           // Immediately login the user
      	 $token = auth()->login($user);

      	return $this->respondWithToken($token);
        }
        
        /**
        * Retrieves an Authenticate user
        */
         public function getAuthUser(Request $request) {
    		try {
    			 if (! $user = JWTAuth::parseToken()->authenticate()) {
          			  return response()->json(['user_not_found'], 404);
           		}

                    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                            return response()->json(['token_expired'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                            return response()->json(['token_invalid'], $e->getStatusCode());

                    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                            return response()->json(['token_absent'], $e->getStatusCode());

                    }

                    return response()->json(compact('user'));
   }
      
     /**
     * Login a User
     */ 
     public function login(Request $request) {
     $credentials = $request->only(['email', 'password']);

      if(!$token = auth('api')->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorised'], 401);
      }
      
      return $this->respondWithToken($token);
   }
        
   /**
   *	Logout a user
   */
   public function logout() {
     auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
   }
   
   /**
   * Reset A user Password when forgotten
   */
   public function forgot_password(Request $request)
	{
    $input = $request->all();
    
    $rules = array(
        'email' => "required|email",
    );
    
    $validator = Validator::make($input, $rules);
    
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject($this->getEmailSubject());
            });
            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return \Response::json(array("status" => 200, "message" => trans($response), "data" => array()));
                case Password::INVALID_USER:
                    return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
            }
        } catch (\Swift_TransportException $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        } catch (Exception $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return \Response::json($arr);
}
   
   /**
   *	Generates a token for the logged in users
   */
   protected function respondWithToken($token) {
    return response()->json([
       'access_token' => $token,
       'token_type' => 'bearer',
       'expires_in' => auth('api')->factory()->getTTL() * 60
     ]);
  }
}

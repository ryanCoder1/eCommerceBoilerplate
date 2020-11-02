<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\Http\Controllers\TokenAccess;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
  /**
    * Create a new AuthController instance.
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware('auth:api', ['except' => ['login']]);
   }

   /**
    * Get a JWT token via given credentials.
    *
    * @param  \Illuminate\Http\Request  $request
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');

       if ($token = $this->guard()->attempt($credentials)) {

           return $this->respondWithToken($token);
       }

       return response()->json(['error' => 'Unauthorized'], 401);
   }

   /**
    * Get the authenticated User
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function me()
   {
       return response()->json($this->guard()->user());
   }

   /**
    * Log the user out (Invalidate the token)
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       $this->guard()->logout();

       return response()->json(['message' => 'Successfully logged out']);
   }

   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh()
   {
       return $this->respondWithToken($this->guard()->refresh());
   }

   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token)
   {


       return response()->json([
           'access_token' => $token,
           'user' => $this->guard()->user(),
           'token_type' => 'bearer',
           'friend_id' => 0,
           'nav_outfit' => false,
           'expires_in' => $this->guard()->factory()->getTTL()
       ]);
   }

   /**
    * Get the guard to be used during authentication.
    *
    * @return \Illuminate\Contracts\Auth\Guard
    */
   public static function guard()
   {
       return Auth::guard();
   }
}
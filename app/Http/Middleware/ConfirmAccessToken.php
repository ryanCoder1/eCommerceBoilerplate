<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CookieController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use JWTAuth;
use Cookie;

class ConfirmAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

      // check if db cookie field matches user cookie. if not reset
      // retrieve cookie field from db
      $dbCookie = DB::table('users')
                     ->select('cookie')
                     ->where('email', '=', $request->input('email'))
                     ->orWhere('id', '=', $request->input('userId'))
                     ->get();
    

   }


}

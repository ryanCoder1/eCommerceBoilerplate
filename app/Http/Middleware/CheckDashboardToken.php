<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDashboardToken
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

       $input = $request->all();
      // test if header Authorization matches Cookie access_token

          $header = $input['Authorization'];
          $cookieAccessToken = $request->cookie('access_token');


       // var_dump($header);
       // var_dump($cookieAccessToken);
      if($header == null || $cookieAccessToken == null){
            return response()->json(['error' => 'Unauthorized'], 401);

      }
      else if($header !== $cookieAccessToken){

             return response()->json(['error' => 'Unauthorized'], 401);
         }
         return $next($request);

  }
}

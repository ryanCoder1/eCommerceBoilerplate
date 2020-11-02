<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;

class LogoutController extends Controller
{




  /* Create a controller instance.
  *
  * Logout out and redirect
  */
 public function logout(Request $request)
  {
    Auth::logout();
    return redirect()->route('/login');
  }
}

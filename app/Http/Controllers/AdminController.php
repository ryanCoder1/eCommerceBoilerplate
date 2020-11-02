<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use App\Mail\AdminInvite;
use App\Mail\DashboardVerify;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{



  public function __construct()
  {
      // $this->middleware('admin', ['except' => ['login']]);
  }
  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array  $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {

  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\Admin
   */
  protected function create(Request $request)
  {

    $result = DB::table('admins')
                  ->select('admins.*')
                  ->where('email', '=', $request->input('email'))
                  ->get();

    if($result->isEmpty()){
      $verifyToken = Str::random(40);
        $mailSent = $this->sendEmail($request, $verifyToken);
          if($mailSent){
            $id = DB::table('admins')->insert(
                        [
                          'email' => $request->input('email'),
                          'first_name' => $request->input('firstName'),
                          'last_name' => $request->input('lastName'),
                          'job_title' => $request->input('jobTitle'),
                          'privilege' => $request->input('privilege'),
                          'password' => Hash::make($request->input('password')),
                          'verify_token' => $verifyToken,
                       ]
                    );
                  if($id){
                    return response()->json(['accountcreated' => true]);
                  }
            }else{
                  return response()->json(['accountcreated' => false]);
              }

       }else{
         return response()->json(['emailexists' => true]);
       }
  }
  // send Registeration Email Verification to verify account
  // @param $thisUser = current user
  public function sendEmail($request, $verifyToken)
  {

    $userName = $request->input('firstName'). ' '.$request->input('lastName');
    $userEmail = $request->input('email');

    // Mail service with DashbaordVerify() and params
    Mail::to($request->input('email'))->send(new DashboardVerify($verifyToken, $userName, $userEmail));
     // check if mail failures array > 0 return to frontEnd
      if( count(Mail::failures()) > 0 ) {
        return false;
      }
      // return true if mail has no errors
      return true;
  }


  public function getAdminVerify()
  {
    return view('welcome');
  }


  public function getEmailReturn()
  {
    return view('welcome');
  }


  /**
   * Test validity of token, privilege in DB.
   *
   *  @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function verifyAdminLoginParams(Request $request)
  {
        $result = DB::table('admins')
                      ->select('admins.*')
                      ->where('email', '=', $request->input('email'))
                      ->where('verify_token', '=', $request->input('verifyToken'))
                      ->get();

        if($result->isEmpty()){
            return response()->json(['verifyValid' => false]);
        }else{
            return response()->json(['verifyValid' => true]);
        }
    }

  /**
   * Test validity of token, privilege in DB.
   *
   *  @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function verifyAdminInvite(Request $request)
  {
        $result = DB::table('dashboard_invite')
                      ->select('dashboard_invite.*')
                      ->where('privilege', '=', $request->input('privilege'))
                      ->where('invite_token', '=', $request->input('inviteToken'))
                      ->get();

        if($result->isEmpty()){
            return response()->json(['inviteValid' => false]);
        }else{
            return response()->json(['inviteValid' => true, 'invited_by' => $result[0]->invited_by]);
        }
    }
    // send Admin Invite to create Admin account
    // @param $thisUser = current user
     public function sendAdminInviteEmail(Request $request)
     {

        $adminInviteToken = Str::random(40);
        $name = $request->input('firstName'). ' ' .$request->input('lastName');
        $privilege = $request->input('privilege');
        $invitedBy = $request->input('invitedBy');

        // insert into dashboard_invite on return link from email invite
        $id = DB::table('dashboard_invite')->insert(
                    [
                     'privilege' => $privilege,
                     'invited_by' => $invitedBy,
                     'invite_token' => $adminInviteToken,
                     'created_at' => Carbon::now(),
                   ]
                );
      if($id){
         // Mail service with AdminInvite() and params
         Mail::to($request->input('email'))->send(new AdminInvite($adminInviteToken, $privilege, $invitedBy, $name));
         // check if mail failures array > 0 return to frontEnd
          if( count(Mail::failures()) > 0 ) {
            return response()->json(['mailAdminInvite' => false]);
          }

          return response()->json(['mailAdminInvite' => true]);
      }else{
          return response()->json(['mailAdminInvite' => false]);
      }
     }

    public function getAdminEmailCreate()
    {
      return view('welcome');
    }
}

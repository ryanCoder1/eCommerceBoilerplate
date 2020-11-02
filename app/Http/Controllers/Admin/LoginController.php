<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Cookie;



class LoginController extends Controller
{

  private $access_token;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $data = DB::table('admins')
                      ->select('admins.*')
                      ->where('email', '=', $request->input('email'))
                      ->get();

        if(empty($data)){
            return response()->json(['successemail' => false]);
        } else{
          if( ! Hash::check($request->input('password') , $data[0]->password  ) ){
                    return response()->json(['successpassword' => false]);
                }else{
                  // there is and email and password match
                  // create a timed session token for user in dashboard
                  if($this->setCookieForAdmin()){
                      return response()->json(['success' => true, 'admin' => $data[0], 'access_token' => $this->access_token]);
                  }

                }
        }
    }
    /**
     * Set a cookie that will match DB Admin user cookie.
     * This is used for verification authorization in Admin Dashboard
     * @return true
     */
    public function setCookieForAdmin(){
        // create a timed cookie token for user in dashboard
        $this->access_token = Str::random(50);
        $minutes = 30;
        Cookie::queue(Cookie::make('access_token', $this->access_token, $minutes));

            return true;
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return response()->json(['message' => 'Successfully logged out', 'access_token' => null]);
    }
}

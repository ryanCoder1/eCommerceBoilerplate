<?php

namespace App\Http\Controllers\Clientside;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;

class PersonalInfoController extends Controller
{
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
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function socialMediaShow()
    {

         $socialMedia = DB::table('personal_info_social_media')
                         ->select('personal_info_social_media.*')
                         ->get();

          return response()->json(['socialmedia' => $socialMedia]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function aboutShow()
    {

         $about = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'about')
                         ->get();

          return response()->json(['about' => $about]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberShow()
    {

         $phoneNumber = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();

          return response()->json(['phonenumber' => $phoneNumber]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function emailAddressShow()
    {

         $emailAddress = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();

          return response()->json(['emailaddress' => $emailAddress]);

    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAddressUseShow()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberUseShow()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function socialMediaUseShow()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'socialMedia')
                         ->get();

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function aboutUseShow()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'about')
                         ->get();

          return response()->json(['using' => $use]);

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
}

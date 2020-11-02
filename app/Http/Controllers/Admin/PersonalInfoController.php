<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckDashboardToken;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;

class PersonalInfoController extends Controller
{

    private $id;
    private $info;

    /**
       * Constructor.
       *
       * @return void
       */
      public function __construct()
      {
          $this->middleware(CheckDashboardToken::class);

      }

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
    public function createPhoneNumberInDB()
    {
      $id = DB::table('personal_info')->insertGetId(
           [
            'id' => 0,
            'name' => 'phoneNumber',
            'created_at' => Carbon::now(),
            ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createEmailAddressInDB()
    {
      $id = DB::table('personal_info')->insertGetId(
          [
            'id' => 1,
            'name' => 'emailAddress',
            'created_at' => Carbon::now(),
          ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAboutInDB()
    {
      $id = DB::table('personal_info')->insertGetId(
          [
            'id' => 4,
            'name' => 'about',
            'created_at' => Carbon::now(),
          ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSocialMediaInDB($request)
    {
      // run for loop for insert to DB
      // ordered from 0 to end.
      $count = $request->input('socialMediaCount');
      for($i = 0; $i < $count; $i++){
          $name = $request->input('socialMediaName'.$i);
          $link = $request->input('socialMediaLink'.$i);

           DB::table('personal_info_social_media')->insertGetId(
              [
                'id' => $i,
                'name' => $name,
                'link' => $link,
                'created_at' => Carbon::now(),
              ]
            );
      }

      return true;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSocialMediaAddInDB($request, $countSMDB)
    {
      $socialMedia = DB::table('personal_info_social_media')
                      ->select('personal_info_social_media.*')
                      ->orderBy('id', 'asc')
                      ->get();

      // run loop and put every object name in array for client obj
      $count = $request->input('socialMediaCount');
      $objClient = [];
      for($i = 0; $i < $count; $i++){
          array_push($objClient, $request->input('socialMediaName'.$i));
      }
      // run loop and put every object name in array DB obj
      $countSocialMedia = count($socialMedia);
      $socialMediaArray = [];
      for($i = 0; $i < $countSocialMedia; $i++){
              array_push($socialMediaArray, $socialMedia[$i]->name);
      }
      // get the array_diff of both arrays, if wanting to delete social media name from DB
      $diff = array_diff($objClient, $socialMediaArray);

      // get the top numbers from the difference of counts from frontend to DB
      $countTemp = $count - $countSocialMedia;
      $startFrom = $count - $countTemp;

      for($i = $startFrom; $i < $count; $i++){
          $name = $request->input('socialMediaName'.$i);
          $link = $request->input('socialMediaLink'.$i);

           DB::table('personal_info_social_media')->insertGetId(
              [
                'id' => $i,
                'name' => $name,
                'link' => $link,
                'created_at' => Carbon::now(),
              ]
            );
      }
    // update links in matched obj name(social media name)
      for($i = 0; $i < $count; $i++){
          $name = $request->input('socialMediaName'.$i);
          $link = $request->input('socialMediaLink'.$i);
            if(!empty($link)){
            $updated = DB::table('personal_info_social_media')
                     ->where('name', $name)
                     ->update(
                       [
                         'link' => $link,
                         'created_at' => Carbon::now(),
                         ]
                       );
              }
        }
        // delete any DB social media names that aren't listed in front end
          foreach($diff as $name){
            DB::table('personal_info_social_media')
                    ->where('name', $name)
                    ->delete();
          }
        return true;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSocialMediaSubtractInDB($request, $countSMDB)
    {

      $socialMedia = DB::table('personal_info_social_media')
                      ->select('personal_info_social_media.*')
                      ->orderBy('id', 'asc')
                      ->get();

      // run loop and put every object name in array for client obj
      $count = $request->input('socialMediaCount');
      $objClient = [];
      for($i = 0; $i < $count; $i++){
          array_push($objClient, $request->input('socialMediaName'.$i));
      }
      // run loop and put every object name in array DB obj
      $countSocialMedia = count($socialMedia);
      $socialMediaArray = [];
      for($i = 0; $i < $countSocialMedia; $i++){
              array_push($socialMediaArray, $socialMedia[$i]->name);
      }
      // get the array_diff of both arrays, if wanting to delete social media name from DB
      $diff = array_diff($socialMediaArray, $objClient);
    // update links in matched obj name(social media name)
      for($i = 0; $i < $count; $i++){
          $name = $request->input('socialMediaName'.$i);
          $link = $request->input('socialMediaLink'.$i);
            if(!empty($link)){
            $updated = DB::table('personal_info_social_media')
                     ->where('name', $name)
                     ->update(
                       [
                         'name' => $name,
                         'link' => $link,
                         'created_at' => Carbon::now(),
                         ]
                       );
              }
        }
      // delete any DB social media names that aren't listed in front end
        foreach($diff as $name){
          DB::table('personal_info_social_media')
                  ->where('name', $name)
                  ->delete();
        }

       return true;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPhoneNumberUseInDB()
    {
      $id = DB::table('personal_info_use')->insertGetId(
           [
            'id' => 0,
            'name' => 'phoneNumber',
            'using' => 0,
            'created_at' => Carbon::now(),
            ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createEmailAddressUseInDB()
    {
      $id = DB::table('personal_info_use')->insertGetId(
          [
            'id' => 1,
            'name' => 'emailAddress',
            'using' => 0,
            'created_at' => Carbon::now(),
          ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSocialMediaUseInDB()
    {
      $id = DB::table('personal_info_use')->insertGetId(
          [
            'id' => 2,
            'name' => 'socialMedia',
            'using' => 0,
            'created_at' => Carbon::now(),
          ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAboutUseInDB()
    {
      $id = DB::table('personal_info_use')->insertGetId(
           [
            'id' => 4,
            'name' => 'about',
            'using' => 0,
            'created_at' => Carbon::now(),
            ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
        }

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
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberShowCreate(Request $request)
    {

         $phoneNumber = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();
         // if NO phoneNumber field in DB. create field in table.
          if(count($phoneNumber) < 1){
            $this->createPhoneNumberInDB();
              return $this->showPhoneNumber();
          }

          return response()->json(['phonenumber' => $phoneNumber]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberShowStore(Request $request)
    {

         $phoneNumber = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();

            // if $phoneNumber is in DB, see if match before updating.
            if($phoneNumber[0]->info != $request->input('info')){
              if($this->editPhoneNumber($request->input('info'))){
                return response()->json(['success' => true]);
              }else{
                // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
              }
            }else{
              return response()->json(['phonenumber' => $phoneNumber]);
            }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function showPhoneNumber()
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
     *
     * @return \Illuminate\Http\Response
     */
    public function showPhoneNumberUse()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();

          return response()->json(['using' => $use]);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $request
     * @return \Illuminate\Http\Response
     */
    public function editPhoneNumber($info)
    {
      // update table if $count != 0. meaning there is a row in the table

      $updated = DB::table('personal_info')
          ->where('name', 'phoneNumber' )
          ->update(['info' => $info]);
        // update success if GTR than 0
        if($updated > 0){
          return true;
        }else{
          return false;
        }
    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberUseShowCreate()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();
         // if NO phoneNumber field in DB. create field in table.
          if(count($use) < 1){
            $this->createPhoneNumberUseInDB();
              return $this->showPhoneNumberUse();
          }

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function phoneNumberUseShowStore(Request $request)
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'phoneNumber')
                         ->get();

            // if $phoneNumber is in DB, see if match before updating.
            if($use[0]->using != $request->input('using')){
              if($this->editPhoneNumberUse($request)){
                return response()->json(['success' => true]);
              }else{
                // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
              }
            }else{
              return response()->json(['using' => $use]);
            }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function editPhoneNumberUse($request)
    {
      $using = null;
      // change boolean to 1 or 0 according to boolean standards
      if($request->input('using') === true){
        $using = 1;
      }else{
        $using = 0;
      }
      $updated = DB::table('personal_info_use')
                ->where('name', 'phoneNumber')
                ->update(
                  [
                    'using' => $using,
                    'created_at' => Carbon::now(),
                    ]
                  );

        if($updated > 0){
            return true;
        }else{
          return false;
        }
    }


    /**********************************

    START OF EMAIL ADDRESS METHODS

    **********************************/

    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function emailAddressShowCreate(Request $request)
    {

         $emailAddress = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();
         // if NO phoneNumber field in DB. create field in table.
          if(count($emailAddress) < 1){
            $this->createEmailAddressInDB();
              return $this->showEmailAddress();
          }

          return response()->json(['emailaddress' => $emailAddress]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function emailAddressShowStore(Request $request)
    {

         $emailAddress = DB::table('personal_info')
                         ->select('personal_info.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();

            // if $phoneNumber is in DB, see if match before updating.
            if($emailAddress[0]->info != $request->input('info')){
              if($this->editEmailAddress($request->input('info'))){
                return response()->json(['success' => true]);
              }else{
                // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
              }
            }else{
              return response()->json(['emailaddress' => $emailAddress]);
            }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function showEmailAddress()
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
    public function showEmailAddressUse()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();

          return response()->json(['using' => $use]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $request
     * @return \Illuminate\Http\Response
     */
    public function editEmailAddress($info)
    {
      // update table if $count != 0. meaning there is a row in the table

      $updated = DB::table('personal_info')
          ->where('name', 'emailAddress' )
          ->update(['info' => $info]);
        // update success if GTR than 0
        if($updated > 0){
          return true;
        }else{
          return false;
        }
    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAddressUseShowCreate()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();
         // if NO phoneNumber field in DB. create field in table.
          if(count($use) < 1){
            $this->createEmailAddressUseInDB();
              return $this->showEmailAddressUse();
          }

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function emailAddressUseShowStore(Request $request)
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'emailAddress')
                         ->get();

            // if $phoneNumber is in DB, see if match before updating.
            if($use[0]->using != $request->input('using')){
              if($this->editEmailAddressUse($request)){
                return response()->json(['success' => true]);
              }else{
                // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
              }
            }else{
              return response()->json(['using' => $use]);
            }

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\
     * @return \Illuminate\Http\Response
     */
    public function editEmailAddressUse($request)
    {
      $using = null;
      // change boolean to 1 or 0 according to boolean standards
      if($request->input('using') === true){
        $using = 1;
      }else{
        $using = 0;
      }
      $updated = DB::table('personal_info_use')
                ->where('name', 'emailAddress')
                ->update(
                  [
                    'using' => $using,
                    'created_at' => Carbon::now(),
                    ]
                  );

        if($updated > 0){
            return true;
        }else{
          return false;
        }
    }


        /**********************************

        START OF SOCIAL MEDIA METHODS

        **********************************/

        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function socialMediaShowCreate(Request $request)
        {

             $socialMedia = DB::table('personal_info_social_media')
                             ->select('personal_info_social_media.*')
                             ->get();
              // count from social media DB
              $countSMDB = count($socialMedia);
             // if NO phoneNumber field in DB. create field in table.
              if($countSMDB < 1){
                $this->createSocialMediaInDB($request);
                   return $this->showSocialMedia();

              }
              // if a new social media object was added this updates in DB.
              else if($countSMDB < $request->input('socialMediaCount')){
                  $this->createSocialMediaAddInDB($request, $countSMDB);
                    return $this->showSocialMedia();
              }
              else if($countSMDB > $request->input('socialMediaCount')){
                  $this->createSocialMediaSubtractInDB($request, $countSMDB);
                    return $this->showSocialMedia();
              }
              else if($countSMDB == $request->input('socialMediaCount')){
                  $this->createSocialMediaAddInDB($request, $countSMDB);
                   return $this->showSocialMedia();
              }

        }

        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function showSocialMedia()
        {

             $socialMedia = DB::table('personal_info_social_media')
                             ->select('personal_info_social_media.*')
                             ->get();

              return response()->json(['socialmedia' => $socialMedia]);

        }
        /**
         * Display the specified resource.
         *
         *
         * @return \Illuminate\Http\Response
         */
        public function socialMediaUseShowCreate()
        {

             $use = DB::table('personal_info_use')
                             ->select('personal_info_use.*')
                             ->where('name', '=', 'socialMedia')
                             ->get();
             // if NO phoneNumber field in DB. create field in table.
              if(count($use) < 1){
                $this->createSocialMediaUseInDB();
                  return $this->showSocialMediaUse();
              }

              return response()->json(['using' => $use]);

        }
        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function socialMediaUseShowStore(Request $request)
        {

             $use = DB::table('personal_info_use')
                             ->select('personal_info_use.*')
                             ->where('name', '=', 'socialMedia')
                             ->get();

                // if $phoneNumber is in DB, see if match before updating.
                if($use[0]->using != $request->input('using')){
                  if($this->editSocialMediaUse($request)){
                    return response()->json(['success' => true]);
                  }else{
                    // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
                  }
                }else{
                  return response()->json(['using' => $use]);
                }

        }
        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function editSocialMediaUse($request)
        {
          $using = null;
          // change boolean to 1 or 0 according to boolean standards
          if($request->input('using') === true){
            $using = 1;
          }else{
            $using = 0;
          }
          $updated = DB::table('personal_info_use')
                    ->where('name', 'socialMedia')
                    ->update(
                      [
                        'using' => $using,
                        'created_at' => Carbon::now(),
                        ]
                      );

            if($updated > 0){
                return true;
            }else{
              return false;
            }
        }


/*******************************

ABOUT METHODS

*******************************/
        /**
         * Display the specified resource.
         *
         * @param  \App\SliderFeatured  $sliderFeatured
         * @return \Illuminate\Http\Response
         */
        public function aboutShowCreate(Request $request)
        {

             $about = DB::table('personal_info')
                             ->select('personal_info.*')
                             ->where('name', '=', 'about')
                             ->get();
             // if NO phoneNumber field in DB. create field in table.
              if(count($about) < 1){
                $this->createAboutInDB();
                  return $this->showAbout();
              }

              return response()->json(['about' => $about]);

        }
        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function aboutShowStore(Request $request)
        {

             $about = DB::table('personal_info')
                             ->select('personal_info.*')
                             ->where('name', '=', 'about')
                             ->get();

                // if $about is in DB, see if match before updating.
                if($about[0]->info != $request->input('info')){
                  if($this->editAbout($request->input('info'))){
                    return response()->json(['success' => true]);
                  }else{
                    // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
                  }
                }else{
                  return response()->json(['about' => $about]);
                }

        }
        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function showAbout()
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
         *
         * @return \Illuminate\Http\Response
         */
        public function showAboutUse()
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
         * @param  string $request
         * @return \Illuminate\Http\Response
         */
        public function editAbout($info)
        {
          // update table if $count != 0. meaning there is a row in the table

          $updated = DB::table('personal_info')
              ->where('name', 'about' )
              ->update(['info' => $info]);
            // update success if GTR than 0
            if($updated > 0){
              return true;
            }else{
              return false;
            }
        }
        /**
         * Display the specified resource.
         *
         *
         * @return \Illuminate\Http\Response
         */
        public function aboutUseShowCreate()
        {

             $use = DB::table('personal_info_use')
                             ->select('personal_info_use.*')
                             ->where('name', '=', 'about')
                             ->get();
             // if NO phoneNumber field in DB. create field in table.
              if(count($use) < 1){
                $this->createAboutUseInDB();
                  return $this->showAboutUse();
              }

              return response()->json(['using' => $use]);

        }
        /**
         * Display the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function aboutUseShowStore(Request $request)
        {

             $use = DB::table('personal_info_use')
                             ->select('personal_info_use.*')
                             ->where('name', '=', 'about')
                             ->get();

                // if $phoneNumber is in DB, see if match before updating.
                if($use[0]->using != $request->input('using')){
                  if($this->editAboutUse($request)){
                    return response()->json(['success' => true]);
                  }else{
                    // 500 error. phonenumber can't be updated in phoneNumberShowStoreCreate method
                  }
                }else{
                  return response()->json(['using' => $use]);
                }

        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\
         * @return \Illuminate\Http\Response
         */
        public function editAboutUse($request)
        {
          $using = null;
          // change boolean to 1 or 0 according to boolean standards
          if($request->input('using') === true){
            $using = 1;
          }else{
            $using = 0;
          }
          $updated = DB::table('personal_info_use')
                    ->where('name', 'about')
                    ->update(
                      [
                        'using' => $using,
                        'created_at' => Carbon::now(),
                        ]
                      );

            if($updated > 0){
                return true;
            }else{
              return false;
            }
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

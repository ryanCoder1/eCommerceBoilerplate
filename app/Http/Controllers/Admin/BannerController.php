<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckDashboardToken;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;
use Image;

class BannerController extends Controller
{

  private $imageName;
  private $path;

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

       $images = DB::table('banner')
                       ->select('banner.*')
                       ->get();

    // set variables for image upload for storage and DB insert.
           $this->path = hash( 'sha256', time());
           $files =  $request->file('file');
           $caption = $request->input('caption');
           $title = $request->input('title');
           $this->imageName = $files->getClientOriginalName();
           $fileSize = $files->getClientSize();
           $fileMimeType = $files->getMimeType();



      // if storage of file image is successful, proceed
       if($this->resizeBannerImage($files)){

        $id = DB::table('banner')->insertGetId(
             [
               'title' => $title,
               'caption' => $caption,
               'image_name' => $this->imageName,
               'image_mime' => $fileMimeType,
               'image_path' => $this->path,
               'image_size' => $fileSize,
               'created_at' => Carbon::now(),
              ]
          );
          if($id){
            return response()->json(['success' => true]);
          }else{
            // error msg about Id not created
            $this->error500Email('Banner store not returning ID so insert failure!');
          }
        }else{
          // error msg about not storing properly
          $this->error500Email('Banner store not storing image properly returned false!');
        }

 }

 /**
  * Show the form for editing the specified resource.
  *
  * @param  \App\Banner
  * @return \Illuminate\Http\Response
  */
 public function bannerChoose(Request $request)
 {
   // get the count of banners sent to server
   $count = $request->input('count');
   $banIds = [];
   // run through banners and update Show_on_website
   // also push array of bannerId for future testing
  for($i = 0; $i < $count; $i++){
    $bannerId = $request->input('bannerId'. $i);
    $showOnWebsite = $request->input('show_on_website'. $i);
    array_push($banIds, $bannerId);
   $updated = DB::table('banner')
             ->where('id', $bannerId)
             ->update(
               [
                 'show_on_website' => $showOnWebsite,
                 'created_at' => Carbon::now(),
                 ]
               );
      }
      $DBIds = [];
      // pull all the banners in DB
      $banner = DB::table('banner')
                      ->select('banner.*')
                      ->get();
      // push all banner ID's into array from DB
      for($i = 0; $i < count($banner); $i++){
        $banId = $banner[$i]->id;
        array_push($DBIds, $banId);
      }
      // find difference of arrays from client side to DB table.
      $result = array_diff($DBIds ,$banIds);

      $isSuccess = [];
      // with difference of arrays, change all Show_on_website to 0 that
      // didn't come from client array.
      foreach($result as $id){

      $success = DB::table('banner')
                 ->where('id', $id)
                 ->update(
                   [
                     'show_on_website' => 0,
                     'created_at' => Carbon::now(),
                     ]
                   );

            array_push($isSuccess, $success);
          }
     // test that the count of both variables match
     // meaning what was updated is equal to how much there was to update
     if(count($isSuccess) == count($result)){
         return response()->json(['success' => true]);
     }else{
       return error500Email('Updating Match count not working in banenrChoose Method');
     }
 }
 /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function show()
  {

    $banners = DB::table('banner')
                    ->select('banner.*')
                    ->get();

        return response()->json(['banners' => $banners]);
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
     * @param  array $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // loop thru all incoming delete banner array elements
        for($i = 0; $i < $request->input('count'); $i++){

          // retrieve all images from product_id
          $images = DB::table('banner')
                          ->select('banner.*')
                          ->where('id', $request->input('bannerId'. $i))
                          ->get();
            // var_dump($images);
          if(count($images) > 0){
            // loop through all images and delete one at a time
            for($m = 0; $m < count($images); $m++){
                $id = $images[$m]->id;
                $filePath = $images[$m]->image_path;
                $fileName = $images[$m]->image_name;

          // if storage disk path is deleted then delete record from db table also
           if(Storage::disk('uploads')->delete($filePath.'/'.$fileName)){
                 $this->deleteDirectoryIfEmpty($filePath);
                 DB::table('banner')
                        ->where('id', $id)
                        ->delete();

            }
          }
        }else {
          // 500 error.
          return error500Email('Deleting of Banner has problem in destory method in Admin/BannerController');
        }

      }// end of for loop for request ids
        return response()->json(['success' => true]);


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBannerUseInDB()
    {
      $id = DB::table('personal_info_use')->insertGetId(
           [
            'id' => 3,
            'name' => 'banner',
            'using' => 0,
            'created_at' => Carbon::now(),
            ]
        );
        if($id){
           return true;
        }else{
          // error msg about Id not created
          return error500Email('Problem creating Banner Use in createBannerUsInDB method Admin/bannerController');
        }

    }
    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function bannerUseShowCreate()
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'banner')
                         ->get();
         // if NO phoneNumber field in DB. create field in table.
          if(count($use) < 1){
            $this->createBannerUseInDB();
              return $this->showBannerUse();
          }

          return response()->json(['using' => $use]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Banner
     * @return \Illuminate\Http\Response
     */
    public function bannerUseShowStore(Request $request)
    {

         $use = DB::table('personal_info_use')
                         ->select('personal_info_use.*')
                         ->where('name', '=', 'banner')
                         ->get();

            // if $phoneNumber is in DB, see if match before updating.
            if($use[0]->using != $request->input('using')){
              if($this->editBannerUse($request)){
                return response()->json(['success' => true]);
              }else{
                // 500 error.
                return error500Email('Updating of Banner Use has problem in banenrUseShowStore method in Admin/BannerController');
              }
            }else{
              return response()->json(['using' => $use]);
            }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner
     * @return \Illuminate\Http\Response
     */
    public function editBannerUse($request)
    {
      $using = null;
      // change boolean to 1 or 0 according to boolean standards
      if($request->input('using') === true){
        $using = 1;
      }else{
        $using = 0;
      }
      $updated = DB::table('personal_info_use')
                ->where('name', 'banner')
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
     * Resize product images to portrait dimensions
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function resizeBannerImage($file)
    {
        // if $file is not empty run image resize
        // if $file empty return true to move forward with process
        if(!empty($file)){
             $img = Image::make($file->path());
             $img->resize(800, 700);
             $img->save(public_path('images/'.$this->imageName));

            $imgPath = file_get_contents('images/'.$this->imageName);
              //Now use laravel filesystem.


             if(Storage::disk('uploads')->put($this->path.'/'.$img->basename, $imgPath)){
               return true;
             }else{
               return false;
             }
           }else{
             return true;
           }

    }
    /**
     * Send Error Email and create 500 server error
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function error500Email($error){

      Mail::to(EMAIL_ERRORS_TO)->send(new ErrorsEmail($error));
      return response()->json(['message' => $error], 500);

    }


    /**
     * Delete Directory in storage if empty
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function deleteDirectoryIfEmpty($filePath){

        // Target directory.
        if($filePath != null){
            $files = Storage::Disk('uploads')->files($filePath);
              if(count($files) == 0){
                Storage::Disk('uploads')->deleteDirectory($filePath);
                return true;
              }else{
                return false;
              }
          }

    }
}

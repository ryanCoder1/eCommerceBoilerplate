<?php

namespace App\Http\Controllers\Admin;

use App\SliderFeatured;
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

class SliderFeaturedController extends Controller
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

       $images = DB::table('slider_featured')
                       ->select('slider_featured.*')
                       ->where('id', 0)
                       ->orWhere('id', 1)
                       ->orWhere('id', 2)
                       ->orWhere('id', 3)
                       ->orWhere('id', 4)
                       ->get();

      // if $images has object arrays then destroyFeaturedImage for each object
        if(count($images) > 0){
            for($i = 0; $i < count($images); $i++){
                $this->destroyFeaturedImage($images[$i]->id);
            }
        }

        //set path hash outside of loop so it's constant for all uploaded images inserted into DB
        $this->path = hash( 'sha256', time());

       $insertData = array();
       for($i = 1; $i <= $request->input('imageFileCount'); $i++){
         if($request->file('file'. $i) !== null){
           $files =  $request->file('file'. $i);
           $caption = $request->input('caption'. $i);
           $title = $request->input('title'. $i);

         }
         // break loop if there is a null file
         if($request->file('file'. $i) == null){
           break;
         }

       if(!empty($files)){
           $this->imageName = $files->getClientOriginalName();
           $fileSize = $files->getClientSize();
           $fileMimeType = $files->getMimeType();

         }

      //  // if storage of file image is successful, proceed
       if($this->resizeFeaturedImageLandscape($files)){
         $data = array(
           'id' => $i - 1,
           'title' => $title,
           'caption' => $caption,
           'image_name' => $this->imageName,
           'image_mime' => $fileMimeType,
           'image_path' => $this->path,
           'image_size' => $fileSize,
           'created_at' => Carbon::now(),
          );

          $insertData[] = $data;
        }
      }


        DB::table('slider_featured')->insert($insertData);

     return response()->json(['success' => true]);

 }

    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

         $images = DB::table('slider_featured')
                         ->select('slider_featured.*')
                         ->where('id', 0)
                         ->orWhere('id', 1)
                         ->orWhere('id', 2)
                         ->orWhere('id', 3)
                         ->orWhere('id', 4)
                         ->get();

          return response()->json(['featured' => $images]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function showFeaturedUse()
    {

         $use = DB::table('slider_use')
                         ->select('slider_use.*')
                         ->where('slider_name', '=', 'featured')
                         ->get();

          return response()->json(['using' => $use]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function editFeaturedUse(Request $request)
    {
      $using = null;
      // change boolean to 1 or 0 according to boolean standards
      if($request->input('using') === true){
        $using = 1;
      }else{
        $using = 0;
      }
      $updated = DB::table('slider_use')
                ->where('slider_name', 'featured')
                ->update(
                  [
                    'using' => $using,
                    'created_at' => Carbon::now(),
                    ]
                  );

        if($updated > 0){
            return response()->json(['success' => true]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SliderFeatured $sliderFeatured)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function destroy(SliderFeatured $sliderFeatured)
    {
        //

    }

    /**
     * Resize product images to portrait dimensions
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function resizeFeaturedImageLandscape($file)
    {
        // if $file is not empty run image resize
        // if $file empty return true to move forward with process
        if(!empty($file)){
             $img = Image::make($file->path());
             $img->resize(1260, 680);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyFeaturedImage($id)
    {
          // retrieve all images from product_id
          $featured = DB::table('slider_featured')
                          ->select('slider_featured.*')
                          ->where('id', '=', $id)
                          ->get();
          // retrieve file image info from $product
          $fileName = $featured[0]->image_name;
          $filePath = $featured[0]->image_path;

          // if storage disk path is deleted then delete record from db table also
             Storage::disk('uploads')->delete($filePath.'/'.$fileName);
             $this->deleteDirectoryIfEmpty($filePath);
             DB::table('slider_featured')->where('id',$id)->delete();


    }
    public function deleteDirectoryIfEmpty($filePath){

        // Target directory.
        if($filePath != null){
          $files = Storage::Disk('uploads')->files($filePath);
            if(count($files) == 0){
              Storage::Disk('uploads')->deleteDirectory($filePath);
            }
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
     * Resize product images to portrait dimensions
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function resizeProductImagePortrait($file)
    {
        // if $file is not empty run image resize
        // if $file empty return true to move forward with process
        if(!empty($file)){
             $img = Image::make($file->path());
             $img->resize(300, 550, function ($constraint) {
                 $constraint->aspectRatio();
             });
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

}

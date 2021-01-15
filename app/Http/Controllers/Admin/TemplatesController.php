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

class TemplatesController extends Controller
{

    private $imagePath;
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
    public function templateStore(Request $request)
    {


          $file = $request->file('file');
          $this->imageName = $file->getClientOriginalName();
          $fileSize = $file->getClientSize();
          $fileMimeType = $file->getMimeType();
          $this->path = hash( 'sha256', time());

      // if storage of file image is successful, proceed
      if($this->resizeTemplateImage($file, 'store template')){
        $id = DB::table('templates')->insertGetId(
                [
                 'name' => $request->input('template_name'),
                 'image_name' => $this->imageName,
                 'image_mime' => $fileMimeType,
                 'image_path' => $this->path,
                 'image_size' => $fileSize,
                 'created_at' => Carbon::now(),
               ]
            );
        if($id){
          return  response()->json(['success' => true]);
          }else{
          return  $this->error500Email('Failed to save template in store');
          }
      }else{
        return  $this->error500Email('Failed to store image in template store');
      }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Templates
     * @return \Illuminate\Http\Response
     */
    public function templatesShow()
    {

         $templates = DB::table('templates')
                         ->select('templates.*')
                         ->get();

          return response()->json(['templates' => $templates]);

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Templates
     * @return \Illuminate\Http\Response
     */
    public function showTemplateUse()
    {

         $use = DB::table('template_use')
                         ->select('template_use.*')
                         ->get();
    // if $use array == 0 the insert table field as null for later update                     
        if(count($use) === 0){
          $id = DB::table('template_use')->insert(
                  [
                   'id' => 0,
                   'template_name' => null,
                   'created_at' => Carbon::now(),
                 ]
              );
            }

          return response()->json(['using' => $use]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Templates
     * @return \Illuminate\Http\Response
     */
    public function editTemplateUse(Request $request)
    {


      $using = 1;

      $updated = DB::table('template_use')
                ->where('id', 0)
                ->update(
                  [
                    'template_name' => $request->input('template_name'),
                    'using' => $using,
                    'created_at' => Carbon::now(),
                    ]
                  );

        if($updated > 0){
            return response()->json(['success' => true]);
        }
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
    /**
     * Cause 500 error and message thru email
     *
     * @param  $error Message sent thru email
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
    public function resizeTemplateImage($file, $method)
    {
        // if $file is not empty run image resize
        // if $file empty return true to move forward with process

        if(!empty($file)){

             $img = Image::make($file->path())->resize(400, 400);

             $img->save(public_path('images/'.$this->imageName));
             $imgPath = file_get_contents('images/'.$this->imageName);
              //Now use laravel filesystem.


             if(Storage::disk('uploads')->put($this->path.'/'.$img->basename, $imgPath)){

               return true;
             }else{
                 return $this->error500Email('Resize Template Image not working from '. $method);
             }
           }else{

             return true;
           }

    }


    /**
     * Store image in storage 'uploads'
     *
     * @param  obj File
     * @return \Illuminate\Http\Response
     */
    public function fileStorage($file){
      if(!empty($file)){
        $filename = $file->getClientOriginalName();

        if(Storage::disk('uploads')->put($this->path.'/'.$filename,  File::get($file))) {
           return true;
        }else{
            return false;
        }
      }else{
        return true;
      }
    }
  }

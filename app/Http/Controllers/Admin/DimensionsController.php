<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckDashboardToken;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;


class DimensionsController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDimension(Request $request)
    {

          $id = DB::table('product_dimensions')->insertGetId(
                    [
                     'dimension' => $request->input('dimension'),
                     'created_at' => Carbon::now(),
                   ]
                );


        if($id){
              return  response()->json(['success' => true]);
          }else{
              // 500 error for store dimension in method dimensionStore
              return $this->error500Email('store dimension in method dimensionStore');
          }

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\DimensionsController
    * @return \Illuminate\Http\Response
    */
    public static function showDimensions(Request $request)
    {
      $dimensions = DB::table('product_dimensions')
                      ->select('product_dimensions.*')
                      ->orderBy('order_number')
                      ->get();

      return response()->json(['dimensions' => $dimensions]);
    }

    /**
   * Display the specified resource.
   *
   * @param  \App\DimensionsController
   * @return \Illuminate\Http\Response
   */
  public function dimensionsOrderUpdate(Request $request)
  {


  //  run loop of dimensions array to update each order_number
    for($i = 0; $i < $request->input('count'); $i++){
      $updated = DB::table('product_dimensions')
                      ->where('dimension', $request->input('dimension'. $i))
                      ->update(['order_number' => $request->input('order_number'. $i)]);
        }

     return response()->json(['success' => true]);
  }

    /**
   * Display the specified resource.
   *
   * @param  \App\DimensionsController
   * @return \Illuminate\Http\Response
   */
      public function dimensionsDestroy(Request $request){

          // loop thru dimension array and delete all dimensions with
          // key (delete) == true
          for($i = 0; $i < $request->input('count'); $i++){

            if($request->input('dimension_delete'. $i) == "true"){
                 DB::table('product_dimensions')->where('dimension', $request->input('dimension'. $i))->delete();
            }
          }

          return response()->json(['success' => true]);

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
}

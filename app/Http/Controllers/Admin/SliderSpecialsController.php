<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckDashboardToken;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;

require app_path().'/Configs/appconstants.php';

class SliderSpecialsController extends Controller
{

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
      $count = DB::table('slider_specials')
                      ->select('slider_specials.*')
                      ->where('id', '=', 0)
                      ->count();

      // if table returns 0 count rows
        if($count == 0){
              $id = DB::table('slider_specials')->insertGetId(
                  [
                    'product_ids' => $request->input('product_ids'),
                    'created_at' => Carbon::now(),
                  ]
              );
            if($id){
                return response()->json(['success' => true]);
            }
            else{
              return $this->error500Email('Problem with inserting Slider Specials Product_ids with $count == 0');
            }
        }else{
          // update table if $count != 0. meaning there is a row in the table
          $affected = DB::table('slider_specials')
              ->where('id', 0)
              ->update(['product_ids' => $request->input('product_ids')]);

                if($affected){
                    return response()->json(['success' => true]);
                }
                else{
                    return $this->error500Email('Problem with updating Slider Specials Product_ids with $count != 0');
                }
          }
    }
    protected function error500Email($error){

      Mail::to(EMAIL_ERRORS_TO)->send(new ErrorsEmail($error));
      return response()->json(['message' => $error], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
      $result = DB::table('slider_specials')
                      ->select('slider_specials.*')
                      ->where('id', '=', 0)
                      ->get();

      $productArray = array();
      if($result[0]->product_ids != null){
      // explode string into product_ids array
      $productsTrim = rtrim($result[0]->product_ids, ',');
      $productsList = explode(',', $productsTrim);



          // run array of products numbers through loop to retrieve product info
          for($i = 0; $i < count($productsList); $i++){

            $products = DB::table('products')
                            ->select('products.*')
                            ->where('id', '=', $productsList[$i])
                            ->get();

              array_push($productArray, $products[0]);

          }
    }
       return response()->json(['specials' => $productArray]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function showSpecialsUse()
    {

         $use = DB::table('slider_use')
                         ->select('slider_use.*')
                         ->where('slider_name', '=', 'specials')
                         ->get();

          return response()->json(['using' => $use]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function editSpecialsUse(Request $request)
    {
      $using = null;
      // change boolean to 1 or 0 according to boolean standards
      if($request->input('using') === true){
        $using = 1;
      }else{
        $using = 0;
      }
      $updated = DB::table('slider_use')
                      ->where('slider_name', 'specials')
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

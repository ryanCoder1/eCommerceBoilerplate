<?php

namespace App\Http\Controllers\Clientside;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckDashboardToken;
use Carbon\Carbon;
use App\Mail\ErrorsEmail;
use Mail;

class SlidesClientsideController extends Controller
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
    public function showSpecials()
    {
      $result = DB::table('slider_specials')
                      ->select('slider_specials.*')
                      ->where('id', '=', 0)
                      ->get();

      $productArray = array();
      $stylesArray = array();
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

            $styles = DB::table('product_styles')
                              ->select('product_styles.*')
                              ->where('product_id', '=', $productsList[$i])
                              ->groupBy('color')
                              ->get();

             array_push($stylesArray, $styles);
          }
        }
        return response()->json(['specials' => $productArray, 'groups' => $stylesArray]);
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
     * Display the specified resource.
     *
     * @param  \App\SliderFeatured  $sliderFeatured
     * @return \Illuminate\Http\Response
     */
    public function showFeatured()
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

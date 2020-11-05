<?php

namespace App\Http\Controllers\Clientside;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
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
   * @param  \App\Clientside\ProductsController
   * @return \Illuminate\Http\Response
   */
  public function show(Request $request)
  {
      $products = DB::table('products')
                      ->select('products.*')
                      ->get();

      return response()->json(['products' => $products]);
  }
  /**
 * Display the specified resource.
 *
 * @param  \App\Clientside\ProductsController
 * @return \Illuminate\Http\Response
 */
public function showCountProductsCategory(Request $request)
{
    $count = DB::table('products')
                    ->select('category_id', DB::raw('count(*) as num'))
                    ->groupBy('category_id')
                    ->get();

    return response()->json(['productscount' => $count]);
}
/**
* Display the specified resource.
*
* @param  \App\Clientside\ProductsController
* @return \Illuminate\Http\Response
*/
public function showCategoryProducts(Request $request)
{
      $category = DB::table('category_products')
                      ->select('category_products.*')
                      ->where('id', '=', $request->input('category_id'))
                      ->get();

      $products = DB::table('products')
                      ->select('products.*')
                      ->where('category_id', '=', $request->input('category_id'))
                      ->get();


      // order product_styles to product[id] for front end use
      $styles = array();
      $product_styles = '';
      for($i = 0; $i < count($products); $i++){
      $product_styles = DB::table('product_styles')
                      ->select('product_styles.*')
                      ->where('product_id', '=', $products[$i]->id)
                      ->groupBy('color')
                      ->get();

               array_push($styles, $product_styles );
          }

        $product_default_image = DB::table('product_default_image')
                        ->select('product_default_image.*')
                        ->where('id', '=', 1)
                        ->get();

      return response()->json(['success' => true, 'category' => $category, 'products' => $products, 'product_styles' => $styles, 'product_default_image' => $product_default_image]);

}
/**
* Display the specified resource.
*
* @param  \App\Clientside\ProductsController
* @return \Illuminate\Http\Response
*/
public function showProduct(Request $request)
{

      $product = DB::table('products')
                      ->select('products.*')
                      ->where('id', '=', $request->input('product_id'))
                      ->get();


      $product_styles = DB::table('product_styles')
                      ->select('product_styles.*')
                      ->where('product_id', '=', $request->input('product_id'))
                      ->get();
     $product_styles_size = DB::table('product_styles')
                    ->select('product_styles.*')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->groupBy('dimension')
                    ->orderByDesc('dimension')
                    ->get();

      $product_default_image = DB::table('product_default_image')
                        ->select('product_default_image.*')
                        ->where('id', '=', 1)
                        ->get();

      return response()->json(['success' => true, 'product' => $product, 'product_styles' => $product_styles, 'product_styles_size' => $product_styles_size, 'product_default_image' => $product_default_image]);

}
/**
* Display the specified resource.
*
* @param  \App\Clientside\ProductsController
* @return \Illuminate\Http\Response
*/
public function showGroupColor(Request $request)
{

     $product_main_color = DB::table('products')
                    ->select('products.*')
                    ->where('size', '=', $request->input('size'))
                    ->where('id', '=', $request->input('product_id'))
                    ->get();
      $styles = array();
        if(count($product_main_color) > 0){
          // push $product_main_color into array $styles

            array_push($styles, $product_main_color[0] );
          }
      $product_styles_color = DB::table('product_styles')
                     ->select('product_styles.*')
                     ->where('dimension', '=', $request->input('size'))
                     ->where('product_id', '=', $request->input('product_id'))
                     ->get();
        if(count($product_styles_color) > 0){
              // loop thru and push all $product_styles_color results into $styles array
              for($i = 0; $i < count($product_styles_color); $i++){
                     array_push($styles, $product_styles_color[$i] );
                  }
          }
      return response()->json(['success' => true, 'product_styles_color' => $styles]);

}

  /**
 * Display the specified resource.
 *
 * @param  \App\ProductsController
 * @return \Illuminate\Http\Response
 */
public function showGroupProductImage(Request $request)
{

    $groupImage = DB::table('product_styles')
                    ->select('product_styles.*')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->where('color', '=', $request->input('color'))
                    ->whereNotNull('image_name')
                    ->groupBy('color')
                    ->get();

        return response()->json(['groupImage' => $groupImage]);

}
  /**
 * Display the specified resource.
 *
 * @param  \Clientside\ProductsController
 * @return \Illuminate\Http\Response
 */
public function showProductsHome(Request $request)
{

  $showProducts = DB::table('products_on_home')
                  ->select('products_on_home.*')
                  ->get();
    $productsHomeList = [];


    for($i = 0; $i < count($showProducts); $i++){
          array_push($productsHomeList, $showProducts[$i]->product_id);
        }
$productsList = [];
$productsObj = [];
    for($i = 0; $i < count($productsHomeList); $i++){
      $products = DB::table('products')
                      ->select('products.*')
                      ->where('id', '=', $productsHomeList[$i])
                      ->get();
          array_push($productsObj, $products[0]);
          array_push($productsList, $products[0]->id);
        }

    // order product_styles to product[id] for front end use
    $styles = array();
    $product_styles = '';
    for($i = 0; $i < count($productsList); $i++){
    $product_styles = DB::table('product_styles')
                    ->select('product_styles.*')
                    ->where('product_id', '=', $productsList[$i])
                    ->groupBy('color')
                    ->get();

             array_push($styles, $product_styles );
        }

    $product_default_image = DB::table('product_default_image')
                    ->select('product_default_image.*')
                    ->where('id', '=', 1)
                    ->get();

          return response()->json(['success' => true, 'products' => $productsObj, 'product_styles' => $styles, 'product_default_image' => $product_default_image]);

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

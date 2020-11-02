<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
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

require app_path().'/Configs/appconstants.php';


class ProductsController extends Controller
{

    private $path;
    private $imageName;

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
     * Display the specified resource.
     *
     * @param  \App\ProductsController
     * @return \Illuminate\Http\Response
     */
    public static function showCategories(Request $request)
    {
        $categories = DB::table('category_products')
                        ->select('category_products.*')
                        ->get();

        return response()->json(['categories' => $categories]);
    }
    /**
   * Display the specified resource.
   *
   * @param  \App\ProductsController
   * @return \Illuminate\Http\Response
   */
  public static function showCategoryCaption(Request $request)
  {
      $category = DB::table('category_products')
                      ->select('category_products.*')
                      ->where('id', '=', $request->input('id'))
                      ->get();

      return response()->json(['category' => $category]);
  }
    /**
   * Display the specified resource.
   *
   * @param  \App\ProductsController
   * @return \Illuminate\Http\Response
   */
  public static function showProducts(Request $request)
  {
      $products = DB::table('products')
                      ->select('products.*')
                      ->get();

      return response()->json(['products' => $products]);
  }
  /**
 * Display the specified resource.
 *
 * @param  \App\ProductsController
 * @return \Illuminate\Http\Response
 */
public static function showProductEdit(Request $request)
{
    $product = DB::table('products')
                    ->select('products.*')
                    ->where('id', '=', $request->input('product_id'))
                    ->get();

    $product_styles = DB::table('product_styles')
                    ->select('product_styles.*')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->groupBy('color')
                    ->get();

    $product_images = DB::table('product_images')
                    ->select('product_images.*')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->get();
    $product_default_image = DB::table('product_default_image')
                    ->select('product_default_image.*')
                    ->where('id', '=', 1)
                    ->get();

  //  if the $product_images array > 0, send through storage file check.
    if(count($product_images) > 0){
      // test that each $product_image path is valid in storage/files/uploads
        $m = 0;
      for($i = 0; $i < count($product_images); $i++){
          $path = storage_path().'/app/public/images/'.$product_images[$i]->image_path.'/'.$product_images[$i]->image_name;
           if(file_exists($path)) {
             $m++;
            }
          }
          if($m == count($product_images)){
              return response()->json(['success' => true, 'product' => $product, 'product_styles' => $product_styles, 'product_images' => $product_images, 'product_default_image' => $product_default_image]);
          }else{
          return  $this->error500Email('Product edit display images not all in file exists');
          }
        }else{
          // if the $product_images array is 0, send through without a storage file check.
            return response()->json(['success' => true, 'product' => $product, 'product_styles' => $product_styles, 'product_images' => $product_images, 'product_default_image' => $product_default_image]);
        }
}
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function showImagesCount(Request $request)
  {

    $imagesCount = DB::table('product_images')
                    ->select('product_images.*')
                    ->where('product_id', '=', $request->input('product_id'))
                    ->count();

            return response()->json(['images' => $imagesCount]);
  }
  /**
 * Display the specified resource.
 *
 * @param  \App\ProductsController
 * @return \Illuminate\Http\Response
 */
public static function showDimensions(Request $request)
{
    $dimensions = DB::table('product_dimensions')
                    ->select('product_dimensions.*')
                    ->get();

    return response()->json(['dimensions' => $dimensions]);
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
      public function validateImage($file){


         // Build the input for validation
         $fileArray = array('file' => $file);

          // Tell the validator that this file should be an image
          $rules = array(
            'file' => 'image | required | max:20000' // max 20000kb 2.5 mb
          );

          // Now pass the input and rules into the validator
          $validator = Validator::make($fileArray, $rules);

          // Check to see if validation fails or passes
            if ($validator->fails()){
                  // Redirect or return json to frontend with a helpful message to inform the user
                  // that the provided file was not an adequate type
                  return response()->json(['error' => $validator->errors()->getMessages()], 400);
            } else{
               return true;
            }

      }
      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function editProductsHomeStore(Request $request)
      {
        // get number of products from DB

            $productsCount = DB::table('products_on_home')
                            ->select('product_on_home.*')
                            ->count();

        // delete if count products GtR 0
          for($i = 0; $i < $productsCount; $i++){
              DB::table('products_on_home')
                  ->where('id', '=', $i)
                  ->delete();
          }
          $idArray = [];
        // insert into DB new array of productIds
        for($i = 0; $i < $request->input('count'); $i++){

            $id = DB::table('products_on_home')->insert(
                    [
                     'id' => $i,
                     'index_of_product' => $request->input('indexOfProduct'. $i),
                     'product_id' => $request->input('productId'. $i),
                     'created_at' => Carbon::now(),
                   ]
                );
              // after insert add $id to array for compare
                array_push($idArray, $id);
          }
          $countProducts = $request->input('count');
          // test the request count to $id count for proper success
          if(count($id) == settype($countProducts, 'int')){
            return response()->json(['success' => true]);
          }else{
            return response()->json(['failed' => false]);
          }
      }
      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function productsHomeShow(Request $request)
      {
        // get number of products from DB

            $products = DB::table('products_on_home')
                            ->select('products_on_home.*')
                            ->get();

          // test the request count to $id count for proper success
          if(count($products) > 0){
            return response()->json(['products' => $products]);
          }else{
              return  $this->error500Email('Failed to show products in productsHomeShow method');
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


        // set variables to null if request file empty.
        $file = null;
        $fileName = null;
        $fileSize = null;
        $fileMimeType = null;
        $this->path = null;

        if(!empty($request->file('file'))){
            $file = $request->file('file');
            $this->imageName = $file->getClientOriginalName();
            $fileSize = $file->getClientSize();
            $fileMimeType = $file->getMimeType();
            $this->path = hash( 'sha256', time());
          }
        // if storage of file image is successful, proceed
        if($this->resizeProductImagePortrait($file, 'store product')){
          $id = DB::table('products')->insertGetId(
                  [
                   'category_id' => $request->input('category_id'),
                   'product_code' => $request->input('product_code'),
                   'title' => $request->input('title'),
                   'description' => $request->input('description'),
                   'details' => $request->input('details'),
                   'price' => $request->input('price'),
                   'sale_price' => $request->input('sale_price'),
                   'in_stock' => $request->input('in_stock'),
                   'color' => $request->input('color'),
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
            return  $this->error500Email('Failed to save product in product store');
            }
        }else{
          return  $this->error500Email('Failed to store image in product store');
        }
      }

      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function storeProductImages(Request $request)
      {



        $imagesCount = DB::table('product_images')
                        ->select('product_images.*')
                        ->where('product_id', '=', $request->input('product_id'))
                        ->count();


        $insertData = array();
        for($i = 1; $i <= $request->input('imageFileCount'); $i++){
          if($request->file('file'. $i) !== null){
            $this->validateImage($request->file('file'. $i));
            $files =  $request->file('file'. $i);
          }
          if($request->file('file'. $i) == null){
            break;
          }

        if(!empty($files)){
            $this->imageName = $files->getClientOriginalName();
            $fileSize = $files->getClientSize();
            $fileMimeType = $files->getMimeType();
            $this->path = hash( 'sha256', time());
          }

        // if storage of file image is successful, proceed
        if($this->resizeProductImagePortrait($files, 'store product images')){
          $data = array(
            'product_id' => $request->input('product_id'),
            'image_name' => $this->imageName,
            'image_mime' => $fileMimeType,
            'image_path' => $this->path,
            'image_size' => $fileSize,
            'created_at' => Carbon::now(),
           );

           $insertData[] = $data;
         }
       }

       $totalImages = $imagesCount + count($insertData);
         if($imagesCount < 5 && $totalImages < 5){
           DB::table('product_images')->insert($insertData);
            return response()->json(['success' => true, 'imagecount' => $imagesCount, 'imagessaving' => count($insertData)]);

       }else{
            return response()->json(['success' => false, 'imagecount' => $imagesCount, 'imagessaving' => count($insertData)]);
       }


  }
      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function storeProductGroups(Request $request)
      {
          // form the dimension to go in db table
          // dimension_name is from a chosen list
          // dimension is new to add to product_dimensions table

          $dimension = null;
          if(!empty($request->input('dimension_name'))){
            $dimension = $request->input('dimension_name');
          }else {
            $dimension = $request->input('dimension');
            // check if dimension is in table
            $result = DB::table('product_dimensions')
                            ->select('product_dimensions.*')
                            ->where('dimension', '=', $dimension)
                            ->get();

            if(count($result) < 1){
              DB::table('product_dimensions')->insert(
                      [
                       'dimension' => $dimension,
                       'created_at' => Carbon::now(),
                     ]
                  );
                }
            }


            // set variables to null if request file empty.
            $file = null;
            $fileName = null;
            $fileSize = null;
            $fileMimeType = null;
            $this->path = null;

            if(!empty($request->file('file'))){
                $file = $request->file('file');
                $this->imageName = $file->getClientOriginalName();
                $fileSize = $file->getClientSize();
                $fileMimeType = $file->getMimeType();
                $this->path = hash( 'sha256', time());
              }
            // if storage of file image is successful, proceed
        if($this->resizeProductImagePortrait($file, 'store product groups')){
          $id = DB::table('product_styles')->insertGetId(
                  [
                   'product_id' => $request->input('product_id'),
                   'color' => $request->input('color'),
                   'dimension' => $dimension,
                   'price' => $request->input('price'),
                   'sale_price' => $request->input('sale_price'),
                   'in_stock' => $request->input('in_stock'),
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
              return  $this->error500Email('Failed to save group in storeProductGroups method');
            }
          }else{
            return  $this->error500Email('Failed to save groups image in storeProductGroups method');
          }
      }
      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function storeProductCategory(Request $request)
      {

            // check if category is in table
            $result = DB::table('category_products')
                            ->select('category_products.*')
                            ->where('category', '=', $request->input('category'))
                            ->get();

            if(count($result) < 1){
                $id = DB::table('category_products')->insertGetId(
                          [
                           'category' => $request->input('category'),
                           'caption' => $request->input('caption'),
                           'created_at' => Carbon::now(),
                         ]
                      );


          if($id){
                return  response()->json(['success' => true]);
            }else{
                return  response()->json(['success' => false]);
            }
          }else{
              return  response()->json(['in_db' => true]);
          }
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editCategory(Request $request)
      {
        $category = DB::table('category_products')
                        ->select('category_products.*')
                        ->where('id', '=', $request->input('categoryId'))
                        ->get();
        // get caption from db and see if matched incoming request caption
        $caption = $category[0]->caption;
        if($caption == $request->input('caption')){
            return response()->json(['success' => true]);
        }

        $updated = DB::table('category_products')
                        ->where('id', $request->input('categoryId'))
                        ->update(['caption' => $request->input('caption')]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            $this->error500Email('Failed to update product details');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductDetails(Request $request)
      {
        $updated = DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['details' => $request->input('details')]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            $this->error500Email('Failed to update product details');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductDescription(Request $request)
      {
        $data = $request->validate([
                  'description' => 'max:500',

              ]);
                   DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['description' => null]);

        $updated = DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['description' => $data['description']]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            return  $this->error500Email('Failed to update product description');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductPrice(Request $request)
      {
        $product = DB::table('products')
                        ->select('products.*')
                        ->where('id', '=', $request->input('productId'))
                        ->get();
        // get caption from db and see if matched incoming request caption
        $price = $product[0]->price;
        if($price == $request->input('price')){
            return response()->json(['success' => true]);
        }

        $updated = DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['price' => $request->input('price')]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            $this->error500Email('Failed to update product price in method editProductPrice');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductSalePrice(Request $request)
      {
        $product = DB::table('products')
                        ->select('products.*')
                        ->where('id', '=', $request->input('productId'))
                        ->get();
        // get caption from db and see if matched incoming request caption
        $salePrice = $product[0]->sale_price;
        if($salePrice == $request->input('sale_price')){
            return response()->json(['success' => true]);
        }

        $updated = DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['sale_price' => $request->input('sale_price')]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            $this->error500Email('Failed to update product sale_price in method editProductSalePrice');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductInStock(Request $request)
      {
        $product = DB::table('products')
                        ->select('products.*')
                        ->where('id', '=', $request->input('productId'))
                        ->get();
        // get caption from db and see if matched incoming request caption
        $inStock = $product[0]->in_stock;
        if($inStock == $request->input('in_stock')){
            return response()->json(['success' => true]);
        }

        $updated = DB::table('products')
                        ->where('id', $request->input('productId'))
                        ->update(['in_stock' => $request->input('in_stock')]);

          if($updated > 0){
            return response()->json(['success' => true]);
          }else{
            $this->error500Email('Failed to update product in_stock in method editProductInStock');
          }
      }
      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function editProductDefaultImage(Request $request)
      {


        // check if default image is in table
        $result = DB::table('product_default_image')
                        ->select('product_default_image.*')
                        ->where('id', '=', 1)
                        ->get();

        $files = $request->file('file');
        $this->imageName = $files->getClientOriginalName();
        $fileSize = $files->getClientSize();
        $fileMimeType = $files->getMimeType();
      // if default image in table
    if(count($result) > 0){
      if($this->imageName != $result[0]->image_name){
            $this->path = $result[0]->image_path;
      // if storage disk path is deleted then delete record from db table also
       if(Storage::disk('uploads')->delete($this->path.'/'.$result[0]->image_name)){
        $updated = DB::table('product_default_image')
                        ->where('id', 1)
                        ->update(
                          [
                            'image_name' => $this->imageName,
                            'image_mime' => $fileMimeType,
                            'image_path' => $this->path,
                            'image_size' => $fileSize,
                            'created_at' => Carbon::now(),
                      ]
                    );

          if($updated > 0){
                if($this->resizeProductImagePortrait($files, 'edit product default image with update')){
                    return response()->json(['success' => true]);
                }else{
                  $this->error500Email('Failed to update product default image, storage problem. With default image');
              }
          }else{
            $this->error500Email('Failed to update product default image, update problem. With default image');
        }
      }else{
        $this->error500Email('Failed to update product default image, delete storage problem. With default image');
    }
      }
    else{
      if($this->resizeProductImagePortrait($files, 'edit product default image, image match')){
          return response()->json(['success' => true]);
      }else{
        $this->error500Email('Failed to update product default image, storage problem. With default image');
    }

  }
}else{

    // no default image in table
    $this->path =  hash( 'sha256', time());


    $id = DB::table('product_default_image')->insertGetId(
              [
                'id' => 1,
                'image_name' => $this->imageName,
                'image_mime' => $fileMimeType,
                'image_path' => $this->path,
                'image_size' => $fileSize,
                'created_at' => Carbon::now(),
             ]
          );

      if($id == 0){
            if($this->resizeProductImagePortrait($files, 'edit product default image insert with hash')){
                return response()->json(['success' => true]);
            }else{
              $this->error500Email('Failed to update product default image, storage problem. No default image');
          }
      }else{
          $this->error500Email('Failed to update product default image, update/insert problem. No default Image');
    }
       }

  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editProductMainImage(Request $request)
  {

    $this->validateImage($request->file('file'));

    // check if default image is in table
    $result = DB::table('products')
                    ->select('products.*')
                    ->where('id', '=', $request->input('product_id'))
                    ->get();

    $files = $request->file('file');
    $this->imageName = $files->getClientOriginalName();
    $fileSize = $files->getClientSize();
    $fileMimeType = $files->getMimeType();
    $productId = $request->input('product_id');
      // if default image in table
    if(!empty($result[0]->image_name)){

            $this->path = $result[0]->image_path;
      // if storage disk path is deleted then delete record from db table also
       if(Storage::disk('uploads')->delete($this->path.'/'.$result[0]->image_name)){
         if($this->deleteDirectoryIfEmpty($this->path)){
            $this->path =  hash( 'sha256', time());
          }

        $updated = DB::table('products')
                        ->where('id', $productId)
                        ->update(
                          [
                            'image_name' => $this->imageName,
                            'image_mime' => $fileMimeType,
                            'image_path' => $this->path,
                            'image_size' => $fileSize,
                      ]
                    );

          if($updated > 0){
                if($this->resizeProductImagePortrait($files)){
                    return response()->json(['success' => true]);
                }else{
                  return $this->error500Email('Failed to update product main image, storage problem. With main image');
              }
          }else{
          return  $this->error500Email('Failed to update product main image, update problem. With main image');
        }
      }else{
         $this->deleteDirectoryIfEmpty($this->path);
         return $this->noImageInTableEditMain($fileMimeType, $fileSize, $productId, $files);
    }
    }else{
        return $this->noImageInTableEditMain($fileMimeType, $fileSize, $productId, $files);
         }

}



 public function noImageInTableEditMain($fileMimeType, $fileSize, $productId, $files){

         // no default image in table
         $this->path =  hash( 'sha256', time());

         $update2 = DB::table('products')
                         ->where('id', $productId)
                         ->update(
                           [
                             'image_name' => $this->imageName,
                             'image_mime' => $fileMimeType,
                             'image_path' => $this->path,
                             'image_size' => $fileSize,
                       ]
                     );


                   if($update2 > 0){
                     if($this->resizeProductImagePortrait($files, 'no Image in table edit main')){

                         return response()->json(['success' => true]);
                     }else{
                      return $this->error500Email('Failed to update product main image, storage problem. No main image');
                   }
               }else{
                  return $this->error500Email('Failed to update product main image, update problem. No main Image');
               }
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
      public function destroyProductGroup(Request $request)
      {

            $id = $request->input('groupId')['id'];

            // destroys main image associated with product_id and deletes storage
            if(!$this->destroyProductGroupImage($id)){
              $this->error500Email('Destroy product group image not working with group destroy.');
            }
            if(DB::table('product_styles')->where('id',$id)->delete()){
                return response()->json(['deletegroup' => true]);
            }

      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroyProductImage(Request $request)
      {

            $id = $request->input('imageId')['id'];

            $fileName = $request->input('imageId')['image_name'];
            $filePath = $request->input('imageId')['image_path'];

          // if storage disk path is deleted then delete record from db table also
           if(Storage::disk('uploads')->delete($filePath.'/'.$fileName)){
             $this->deleteDirectoryIfEmpty($filePath);
                if(DB::table('product_images')->where('id',$id)->delete()){
                  return response()->json(['deleteimage' => true]);
                }
            }else{
              $this->deleteDirectoryIfEmpty($filePath);
                 if(DB::table('product_images')->where('id',$id)->delete()){
                   return response()->json(['deleteimage' => true]);
                 }
            }
      }
      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroyProductGroupImage($id)
      {
            // retrieve all images from product_id
            $group = DB::table('product_styles')
                            ->select('product_styles.*')
                            ->where('id', '=', $id)
                            ->get();
            // retrieve file image info from $product
            $fileName = $group[0]->image_name;
            $filePath = $group[0]->image_path;

          // if storage disk path is deleted then delete record from db table also
           if(Storage::disk('uploads')->delete($filePath.'/'.$fileName)){
             $this->deleteDirectoryIfEmpty($filePath);
                return true;
            }else{
              $this->deleteDirectoryIfEmpty($filePath);
              return false;
            }
      }
      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroyProductMainImage($product_id)
      {
            // retrieve all images from product_id
            $product = DB::table('products')
                            ->select('products.*')
                            ->where('id', '=', $product_id)
                            ->get();
            // retrieve file image info from $product
            $fileName = $product[0]->image_name;
            $filePath = $product[0]->image_path;

          // if storage disk path is deleted then delete record from db table also
           if(Storage::disk('uploads')->delete($filePath.'/'.$fileName)){
             $this->deleteDirectoryIfEmpty($filePath);
                return true;
            }else{
              $this->deleteDirectoryIfEmpty($filePath);
              return false;
            }
      }

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
      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroyProduct(Request $request)
      {
            $product_id = $request->input('product_id');
            // destroys all images associated with product_id and deletes storage
            $this->destroyProductImages($product_id);

            // destroys main image associated with product_id and deletes storage
            if(!$this->destroyProductMainImage($product_id)){
              return  $this->error500Email('Destroy product main image not working with product destroy.');
            }

            DB::table('products')->where('id',$product_id)->delete();

            DB::table('product_styles')->where('product_id',$product_id)->delete();

            return response()->json(['success' => true]);

      }

      public function destroyProductImages($id){

        // retrieve all images from product_id
        $images = DB::table('product_images')
                        ->select('product_images.*')
                        ->where('product_id', '=', $id)
                        ->get();
          // var_dump($images);
        if(count($images) > 0){
          // loop through all images and delete one at a time
          for($i = 0; $i < count($images); $i++){
              $id = $images[$i]->id;
              $filePath = $images[$i]->image_path;
              $fileName = $images[$i]->image_name;

        // if storage disk path is deleted then delete record from db table also
         if(Storage::disk('uploads')->delete($filePath.'/'.$fileName)){
               $this->deleteDirectoryIfEmpty($filePath);
               DB::table('product_images')->where('id',$id)->delete();

          }
        }
      }
  }

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
      public function resizeProductImagePortrait($file, $method)
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
                   return $this->error500Email('Resize Product Image Portrait not working from '. $method);
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

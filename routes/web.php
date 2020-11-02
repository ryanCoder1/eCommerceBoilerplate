<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');


Route::post('/register', 'Auth\RegisterController@register');

// Verify email on register to activate account
Route::post('/verifyEmail', 'Auth\RegisterController@sendEmailDone');
Route::get('/verify/{email}/{verifyToken}', 'Auth\RegisterController@getEmailReturn')->name('getEmailReturn');
// Verify email for password reset
Route::post('/verifyEmailPassword', 'VerifyEmailsController@createTokenSetEmail');
Route::post('/verifyEmailResetPassword', 'VerifyEmailsController@verifyTokenResetPassword');
Route::get('/resetPassword/{email}/{verifyToken}', 'VerifyEmailsController@getEmailResetReturn')->name('getEmailResetReturn');

Route::group(['prefix' => 'user','middleware' => ['assign.guard:users','jwt.auth']],function ()
{

});
// returned email sent from Admin Invite to employee

Route::post('/admininvite', 'AdminController@sendAdminInviteEmail');
Route::get('/admin/admincreateaccount/{privilege}/{adminVerifyToken}', 'AdminController@getAdminEmailCreate')->name('getAdminEmailCreate');
Route::post('/verifyadmininvite', 'AdminController@verifyAdminInvite');
Route::post('/adminregister', 'AdminController@create');
Route::get('/admin/verify/{email}/{verifyToken}', 'AdminController@getAdminVerify')->name('getAdminVerify');
Route::post('/verifyadminloginparams', 'AdminController@verifyAdminLoginParams');
Route::post('/adminlogin', 'Admin\LoginController@login');
Route::post('/admin/logout', 'AdminAuth\LoginController@logout')->name('logout');



Route::group([ 'middleware' => 'CheckDashboardToken'],function () {

    Route::post('/productstore', 'Admin\ProductsController@store');
    Route::post('/productcategoryretrieve', 'Admin\ProductsController@showCategories');
    Route::post('/productcategorycaptionshow',  'Admin\ProductsController@showCategoryCaption');
    Route::post('/productcategoryedit',  'Admin\ProductsController@editCategory');
    Route::post('/productsretrieve', 'Admin\ProductsController@showProducts');
    Route::post('/dimensionsretrieve', 'Admin\ProductsController@showDimensions');
    Route::post('/productgroups', 'Admin\ProductsController@storeProductGroups');
    Route::post('/productcategorystore', 'Admin\ProductsController@storeProductCategory');
    Route::post('/productimagestore', 'Admin\ProductsController@storeProductImages');
    Route::post('/getimagescount', 'Admin\ProductsController@showImagesCount');
    Route::post('/showproduct', 'Admin\ProductsController@showProductEdit');
    Route::post('/productdeleteimage', 'Admin\ProductsController@destroyProductImage');
    Route::post('/productdeletegroup', 'Admin\ProductsController@destroyProductGroup');
    Route::post('/producteditdetails', 'Admin\ProductsController@editProductDetails');
    Route::post('/producteditdescription', 'Admin\ProductsController@editProductDescription');
    Route::post('/producteditprice', 'Admin\ProductsController@editProductPrice');
    Route::post('/producteditsaleprice', 'Admin\ProductsController@editProductSalePrice');
    Route::post('/producteditinstock', 'Admin\ProductsController@editProductInStock');
    Route::post('/productdefaultimage', 'Admin\ProductsController@editProductDefaultImage');
    Route::post('/productmainimageedit', 'Admin\ProductsController@editProductMainImage');
    Route::post('/productdelete', 'Admin\ProductsController@destroyProduct');
    Route::post('/productshomestore', 'Admin\ProductsController@editProductsHomeStore');
    Route::post('/productshomeshow', 'Admin\ProductsController@productsHomeShow');
    Route::post('/specialsshow', 'Admin\SliderSpecialsController@show');
    Route::post('/specialsstore', 'Admin\SliderSpecialsController@store');
    Route::post('/specialsuse', 'Admin\SliderSpecialsController@showSpecialsUse');
    Route::post('/specialsuseupdate', 'Admin\SliderSpecialsController@editSpecialsUse');
    Route::post('/featuredshow', 'Admin\SliderFeaturedController@show');
    Route::post('/featuredstore', 'Admin\SliderFeaturedController@store');
    Route::post('/featureduse', 'Admin\SliderFeaturedController@showFeaturedUse');
    Route::post('/featureduseupdate', 'Admin\SliderFeaturedController@editFeaturedUse');
    Route::post('/bannerstore', 'Admin\BannerController@store');
    Route::post('/bannershow', 'Admin\BannerController@show');
    Route::post('/bannerchoose', 'Admin\BannerController@bannerChoose');
    Route::post('/banneruseshow', 'Admin\BannerController@bannerUseShowCreate');
    Route::post('/bannerusestore', 'Admin\BannerController@bannerUseShowStore');
    Route::post('/phonenumberuseshow', 'Admin\PersonalInfoController@phoneNumberUseShowCreate');
    Route::post('/phonenumberusestore', 'Admin\PersonalInfoController@phoneNumberUseShowStore');
    Route::post('/phonenumbershow', 'Admin\PersonalInfoController@phoneNumberShowCreate');
    Route::post('/phonenumberstore', 'Admin\PersonalInfoController@phoneNumberShowStore');
    Route::post('/aboutuseshow', 'Admin\PersonalInfoController@aboutUseShowCreate');
    Route::post('/aboutusestore', 'Admin\PersonalInfoController@aboutUseShowStore');
    Route::post('/aboutshow', 'Admin\PersonalInfoController@aboutShowCreate');
    Route::post('/aboutstore', 'Admin\PersonalInfoController@aboutShowStore');
    Route::post('/socialmediastore', 'Admin\PersonalInfoController@socialMediaShowCreate');
    Route::post('/socialmediashow', 'Admin\PersonalInfoController@socialMediaShowCreate');
    Route::post('/socialmediauseshow', 'Admin\PersonalInfoController@socialMediaUseShowCreate');
    Route::post('/socialmediausestore', 'Admin\PersonalInfoController@socialMediaUseShowStore');
    Route::post('/emailaddressuseshow', 'Admin\PersonalInfoController@emailAddressUseShowCreate');
    Route::post('/emailaddressusestore', 'Admin\PersonalInfoController@emailAddressUseShowStore');
    Route::post('/emailaddressshow', 'Admin\PersonalInfoController@emailAddressShowCreate');
    Route::post('/emailaddressstore', 'Admin\PersonalInfoController@emailAddressShowStore');
});
// CLIENT SIDE NO RESTRICTIONS
  Route::post('/specialsuseclient', 'Clientside\SlidesClientsideController@showSpecialsUse');
  Route::post('/specialsshowclient', 'Clientside\SlidesClientsideController@showSpecials');
  Route::post('/featureduseclient', 'Clientside\SlidesClientsideController@showFeaturedUse');
  Route::post('/featuredshowclient', 'Clientside\SlidesClientsideController@showFeatured');
  Route::post('/categoryshowclient', 'Clientside\CategoryController@show');
  Route::post('/productsshowclient', 'Clientside\ProductsController@show');
  Route::post('/showcountproductscategory', 'Clientside\ProductsController@showCountProductsCategory');
  Route::post('/showcategoryproducts', 'Clientside\ProductsController@showCategoryProducts');
  Route::post('/showproductsclienthome', 'Clientside\ProductsController@showProductsHome');
  Route::post('/showgroupproductimage', 'Clientside\ProductsController@showGroupProductImage');
  Route::post('/socialmediashowclient', 'Clientside\PersonalInfoController@socialMediaShow');
  Route::post('/socialmediauseclient', 'Clientside\PersonalInfoController@socialMediaUseShow');
  Route::post('/phonenumberuseclient', 'Clientside\PersonalInfoController@phoneNumberUseShow');
  Route::post('/phonenumbershowclient', 'Clientside\PersonalInfoController@phoneNumberShow');
  Route::post('/emailaddressuseclient', 'Clientside\PersonalInfoController@emailAddressUseShow');
  Route::post('/emailaddressshowclient', 'Clientside\PersonalInfoController@emailAddressShow');
  Route::post('/banneruseclient', 'Clientside\BannerController@bannerUseShow');
  Route::post('/bannershowclient', 'Clientside\BannerController@show');
  Route::post('/aboutuseclient', 'Clientside\PersonalInfoController@aboutUseShow');
  Route::post('/aboutshowclient', 'Clientside\PersonalInfoController@aboutShow');




Route::group(['middleware' => ['assign.guard:admin','jwt.auth']], function () {

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');

});

<?php


use Illuminate\Support\Facades\Route;

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

Route::get('/',[\App\Http\Controllers\Front\HomeController::class,'index']);

Route::prefix('shop')->group(function(){
    Route::get('/product/{id}', [\App\Http\Controllers\Front\ShopController::class,'show']);
    Route::post('/product/{id}', [\App\Http\Controllers\Front\ShopController::class,'postComment']);
    Route::get('', [\App\Http\Controllers\Front\ShopController::class,'index']);
    //param categoryName
    Route::get('/category/{categoryName}',[\App\Http\Controllers\Front\ShopController::class,'category']);
});

//Cart
Route::prefix('cart')->group(function(){
        //because using ajax adding items so route not need id
    Route::get('add', [\App\Http\Controllers\Front\CartController::class,'add']);
    Route::get('update', [\App\Http\Controllers\Front\CartController::class,'update']);
    Route::get('delete', [\App\Http\Controllers\Front\CartController::class,'delete']);
    Route::get('destroy', [\App\Http\Controllers\Front\CartController::class,'destroy']);
    Route::get('/', [\App\Http\Controllers\Front\CartController::class,'index']);
});

//Checkout
Route::prefix('checkout')->middleware('checkLogin')->group(function(){
    Route::get('',[\App\Http\Controllers\Front\CheckOutController::class,'index']);
    Route::post('/',[\App\Http\Controllers\Front\CheckOutController::class,'addOrder']);
    Route::get('/result',[\App\Http\Controllers\Front\CheckOutController::class,'result']);
    Route::get('/vnPayCheck',[\App\Http\Controllers\Front\CheckOutController::class,'vnPayCheck']);
});

//Account and Order
Route::prefix('account')->middleware('checkAfterLogin')->group(function(){
    Route::get('login',[\App\Http\Controllers\Front\AccountController::class,'login']);
    Route::post('login',[\App\Http\Controllers\Front\AccountController::class,'checkLogin']);
    Route::get('logout',[\App\Http\Controllers\Front\AccountController::class,'logout'])->withoutMiddleware('checkAfterLogin');
    Route::get('register',[\App\Http\Controllers\Front\AccountController::class,'register']);
    Route::post('register',[\App\Http\Controllers\Front\AccountController::class,'postRegister']);

    //Order
    Route::prefix('my-order')->middleware('checkLogin')->withoutMiddleware('checkAfterLogin')->group(function(){
        Route::get('/',[\App\Http\Controllers\Front\AccountController::class,'myOrderIndex']);
        Route::get('{id}',[\App\Http\Controllers\Front\AccountController::class,'myOrderShow']);

    });

});

//Admin
Route::prefix('admin')->middleware('checkAdminLogin')->group(function() {
    //Default route
    Route::redirect('','admin/user');

    Route::resource('user',\App\Http\Controllers\Admin\UserController::class);
    Route::resource('category',\App\Http\Controllers\Admin\ProductCategoryController::class);
    Route::resource('brand',\App\Http\Controllers\Admin\BrandController::class);
    Route::resource('product',\App\Http\Controllers\Admin\ProductController::class);
    Route::resource('product/{product_id}/image',\App\Http\Controllers\Admin\ProductImageController::class);
    Route::resource('order',\App\Http\Controllers\Admin\OrderController::class);

    Route::prefix('login')->withoutMiddleware('checkAdminLogin')->middleware('checkAdminAfterLogin')->group(function(){
        Route::get('',[\App\Http\Controllers\Admin\HomeController::class,'getLogin']);
        Route::post('',[\App\Http\Controllers\Admin\HomeController::class,'postLogin']);
    });
    Route::get('logout',[\App\Http\Controllers\Admin\HomeController::class,'logout']);
});






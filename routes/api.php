<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('delete/{id}',[ProductController::class,'deliverOrder']);

Route::post('login', [UserController::class,'login']);
Route::post('register', [UserController::class,'register']);

Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);

Route::get('/products', [ProductController::class,'index']);
Route::post('/upload-file', [ProductController::class,'uploadFile']);
Route::get('/products/{product}', [ProductController::class,'show']);

Route::get('/team', [TeamController::class,'index']);
Route::post('/upload-member', [TeamController::class,'uploadMember']);
Route::get('/team/{member}', [TeamController::class,'show']);

Route::post('/upload-faq', [FaqController::class,'uploadFaq']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::patch('users/{user}', [UserController::class, 'update']);
    Route::get('users/{user}/orders', [UserController::class, 'showOrders']);

    Route::patch('orders/{order}/deliver', [OrderController::class,'deliverOrder']);
    Route::resource('/orders', OrderController::class);

    Route::patch('products/{product}/units/add', [ProductController::class,'updateUnits']);
    Route::resource('/products', ProductController::class)->except(['index', 'show']);

    Route::patch('faqs/{faq}/replied', [FaqController::class,'repliedFaq']);
    Route::resource('/faqs', FaqController::class);


    Route::resource('/team', TeamController::class)->except(['index', 'show']);
});


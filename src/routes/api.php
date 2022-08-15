<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Route
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/social_login', [AuthController::class, 'social_login'])->name('user_social_login');

// Email Verification
Route::post('/resend_email_verification_otp', [AuthController::class, 'resend_email_verification_otp']);
Route::post('/verify_email', [AuthController::class, 'verify_email']);

// forgot password
Route::post('/forgot_password', [AuthController::class, 'send_forgot_password_otp']);
Route::post('/forgot_password_verify_otp', [AuthController::class, 'forgot_password_verify_otp']);
Route::post('/set_new_password', [AuthController::class, 'set_new_password']);


Route::group(['middleware' => 'auth:api'], function () {

    //Logout Route
    Route::get('/logout', [AuthController::class, 'logout']);

    //User Profile Route
    Route::post('/change_password', [AuthController::class, 'change_password']);
    Route::post('/update_profile', [AuthController::class, 'update_profile']);
    Route::get('/get_user_profile', [AuthController::class, 'get_user_profile']);
});
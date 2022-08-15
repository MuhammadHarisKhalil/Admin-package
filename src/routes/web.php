<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;

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

Route::get('/', function () {
    return redirect()->route('AdminLoginPage');
});
Route::get('/admin/login', [AuthController::class, 'admin_Login_Page'])->name('AdminLoginPage');
Route::post('/admin/login', [AuthController::class, 'admin_Login'])->name('AdminLogin');

Route::group(['middleware' => ['auth.admin']], function () {

    // logout Route
    Route::get('/logout', [AuthController::class, 'logout'])->name('AdminLogout');

    // Dashboard Route 
    Route::get('/dashboard', [AdminDashboardController::class, 'dasboard'])->name('admin-dashboard');
});

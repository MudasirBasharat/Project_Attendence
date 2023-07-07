<?php
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/create',[UserController::class,'create'])->name('create');
Route::post('signin',[UserController::class,'signin'])->name('signin');
Route::get('record_user/{email}',[UserController::class,'specific_record_user'])->name('record_user');
Route::get('all_user_record',[UserController::class,'all_user_record'])->name('all_user_record');

Route::middleware(['auth:sanctum', 'lastActivity'])->group(function () {
Route::post('signout',[UserController::class,'signout'])->name('signout');
});


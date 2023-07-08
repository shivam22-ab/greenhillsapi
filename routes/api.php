<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::get('/category',[CategoryController::class,'index']);
Route::post('/store-category',[CategoryController::class,'store']);
Route::get('/category/{slug}',[CategoryController::class,'show']);
Route::put('/update-category/{slug}',[CategoryController::class,'update']);
Route::delete('/delete-category/{slug}',[CategoryController::class,'destroy']);


Route::get('/service',[ServiceController::class,'index']);
Route::post('/store-service',[ServiceController::class,'store']);
Route::get('/service/{slug}',[ServiceController::class,'show']);
Route::put('/update-service/{slug}',[ServiceController::class,'update']);
Route::delete('/delete-service/{slug}',[ServiceController::class,'destroy']);


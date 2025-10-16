<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScentController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ProductController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::post('/store', [CategoryController::class, 'store']);
Route::get('/index', [CategoryController::class, 'index']);
Route::get('/show/{id}', [CategoryController::class, 'show']);
Route::put('/update/{id}', [CategoryController::class, 'update']);
Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);



Route::post('/storeS', [ScentController::class, 'storeS']);
Route::get('/indexS', [ScentController::class, 'indexS']);
Route::get('/showS/{id}', [ScentController::class, 'showS']);
Route::put('/updateS/{id}', [ScentController::class, 'updateS']);
Route::delete('/destroyS/{id}', [ScentController::class, 'destroyS']);


Route::post('/storeM', [ManufacturerController::class, 'storeM']);
Route::get('/indexM', [ManufacturerController::class, 'indexM']);
Route::get('/showM/{id}', [ManufacturerController::class, 'showM']);
Route::put('/updateM/{id}', [ManufacturerController::class, 'updateM']);
Route::delete('/destroyM/{id}', [ManufacturerController::class, 'destroyM']);

Route::post('/storeP', [ProductController::class, 'storeP']);
Route::get('/indexP', [ProductController::class, 'indexP']);

Route::get('category/products/{id}', [ProductController::class ,'getProductsByCategory']);
Route::get('scent/products/{id}', [ProductController::class ,'getProductsByScent']);

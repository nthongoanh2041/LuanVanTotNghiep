<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScentController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ProductController;


// <<<<<<< HEAD
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);



// Route::post('/store', [CategoryController::class, 'store']);
// Route::get('/index', [CategoryController::class, 'index']);
// Route::get('/show/{id}', [CategoryController::class, 'show']);
// Route::put('/update/{id}', [CategoryController::class, 'update']);
// Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);



// Route::post('/storeS', [ScentController::class, 'storeS']);
// Route::get('/indexS', [ScentController::class, 'indexS']);
// Route::get('/showS/{id}', [ScentController::class, 'showS']);
// Route::put('/updateS/{id}', [ScentController::class, 'updateS']);
// Route::delete('/destroyS/{id}', [ScentController::class, 'destroyS']);


// Route::post('/storeM', [ManufacturerController::class, 'storeM']);
// Route::get('/indexM', [ManufacturerController::class, 'indexM']);
// Route::get('/showM/{id}', [ManufacturerController::class, 'showM']);
// Route::put('/updateM/{id}', [ManufacturerController::class, 'updateM']);
// Route::delete('/destroyM/{id}', [ManufacturerController::class, 'destroyM']);

// Route::post('/storeP', [ProductController::class, 'storeP']);
// Route::get('/indexP', [ProductController::class, 'indexP']);

// Route::get('category/products/{id}', [ProductController::class ,'getProductsByCategory']);
// Route::get('scent/products/{id}', [ProductController::class ,'getProductsByScent']);
// =======
Route::post('/register', [AuthController::class, 'register']);                          /*api đăng ký*/
Route::post('/login', [AuthController::class, 'login']);                                /*api đăng nhập*/


/*Categorie*/
Route::post('/store', [CategoryController::class, 'store']);                            /*thêm loại sp*/
Route::get('/index', [CategoryController::class, 'index']);                             /*hiển thị tất cả loại sp*/
Route::get('/show/{id}', [CategoryController::class, 'show']);                          /*hiển thị loại sp theo id*/
Route::put('/update/{id}', [CategoryController::class, 'update']);                      /*sửa loại sp*/
Route::delete('/destroy/{id}', [CategoryController::class, 'destroy']);                 /*xóa loại sp*/


/*Scent*/
Route::post('/storeS', [ScentController::class, 'storeS']);                             /*thêm mùi hương*/
Route::get('/indexS', [ScentController::class, 'indexS']);                              /*hiển thị tất cả mùi hương*/
Route::get('/showS/{id}', [ScentController::class, 'showS']);                           /*hiển thị mùi hương theo id*/
Route::put('/updateS/{id}', [ScentController::class, 'updateS']);                       /*sửa mùi hương*/
Route::delete('/destroyS/{id}', [ScentController::class, 'destroyS']);                  /*xóa mùi hương*/

/*Manufacturer*/
Route::post('/storeM', [ManufacturerController::class, 'storeM']);                      /*thêm NSX*/
Route::get('/indexM', [ManufacturerController::class, 'indexM']);                       /*hiển thị tất cả NSX*/
Route::get('/showM/{id}', [ManufacturerController::class, 'showM']);                    /*hiển thị NSX theo id*/
Route::put('/updateM/{id}', [ManufacturerController::class, 'updateM']);                /*sửa NSX*/
Route::delete('/destroyM/{id}', [ManufacturerController::class, 'destroyM']);           /*xóa NSX*/

/*Product*/
Route::post('/storeP', [ProductController::class, 'storeP']);
Route::get('/indexP', [ProductController::class, 'indexP']);
Route::put('/products/{id}', [ProductController::class, 'updateP']);
// routes/api.php
Route::get('/showP/{id}', [ProductController::class, 'showP']);


Route::get('category/products/{id}', [ProductController::class ,'getProductsByCategory']);              /*hiển thị sản phẩm theo loại sp*/
Route::get('scent/products/{id}', [ProductController::class ,'getProductsByScent']);                    /*hiển thị sản phẩm theo mùi hương*/


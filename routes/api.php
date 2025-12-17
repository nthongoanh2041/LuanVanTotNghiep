<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ScentController;
use App\Http\Controllers\Api\ManufacturerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\ManufacturerRequestController;
use App\Http\Controllers\StockImportController;
use App\Http\Controllers\NotificationController;

Route::get('/notifications/{id}', [NotificationController::class, 'getNotifications']);
Route::get('/notifications/{userId}', [NotificationController::class, 'index']);
Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/read-all/{userId}', [NotificationController::class, 'markAllAsRead']);



Route::post('/register', [AuthController::class, 'register']);                          /*api đăng ký*/
Route::post('/login', [AuthController::class, 'login']);                                /*api đăng nhập*/
Route::get('/user/{id}', [UserController::class, 'show']);

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
Route::post('/manufacturer-request', [ManufacturerRequestController::class, 'storeM']);





/*Product*/
Route::post('/storeP', [ProductController::class, 'storeP']);
Route::get('/indexP', [ProductController::class, 'indexP']);
Route::put('/products/{id}', [ProductController::class, 'updateP']);
// routes/api.php
Route::get('/showP/{id}', [ProductController::class, 'showP']);
Route::get('/stock', [ProductController::class, 'stock']);
Route::get('/stock-import/{id}', [ProductController::class, 'importStock']);
Route::post('/import/{id}', [ProductController::class, 'importStock']);

Route::get('/import/history', [StockImportController::class, 'history']);
Route::get('/import/history/{id}', [StockImportController::class, 'show']);




/*Cart*/
Route::post('/storeC', [CartController::class, 'storeC']);
Route::get('/indexC/{user_id}', [CartController::class, 'indexC']);
Route::put('/updateC/{id}', [CartController::class, 'updateC']);
Route::delete('/destroyC/{id}', [CartController::class, 'destroyC']);
Route::delete('/clearC', [CartController::class, 'clearC']);

/*Order*/


Route::post('/storeO', [OrderController::class, 'storeO']);
Route::get('/indexO', [OrderController::class, 'indexO']);              /*hiển thị tất cả đơn hàng  */
Route::put('/updateO/{id}', [OrderController::class, 'updateO']);            ////cập nhật trạng thái ddonww hàng
Route::get('/showO/{id}', [OrderController::class, 'showO']);            ////Hiển thị chi tiết đơn hàng theo id








Route::get('category/products/{id}', [ProductController::class ,'getProductsByCategory']);              /*hiển thị sản phẩm theo loại sp*/
Route::get('scent/products/{id}', [ProductController::class ,'getProductsByScent']);                    /*hiển thị sản phẩm theo mùi hương*/
// Route::get('category/products/{id}', [ProductController::class ,'getProductsByCategory']);
Route::get('/category/products/{id}', [CategoryController::class, 'products']);
Route::get('/scent/products/{id}', [ScentController::class, 'products']);




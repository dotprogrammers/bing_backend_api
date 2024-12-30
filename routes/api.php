<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Backend\Brand\BrandController;
use App\Http\Controllers\Api\Backend\Category\CategoryController;
use App\Http\Controllers\Api\Backend\Products\ProductController;
use App\Http\Controllers\Api\Backend\SubCategory\SubCategoryController;
use App\Http\Controllers\Api\Backend\Unit\UnitController;
use App\Http\Controllers\Api\Frontend\FrontendController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Frontend Routes
Route::get('get-categories', [FrontendController::class, 'categories']);
Route::get('skill-job', [FrontendController::class, 'skills']);
Route::get('get-sub-categories', [FrontendController::class, 'subCategories']);
Route::get('get-brands', [FrontendController::class, 'brands']);
Route::get('get-units', [FrontendController::class, 'units']);

Route::get('get-products', [FrontendController::class, 'products']);
Route::get('product-detail/{id}', [FrontendController::class, 'productDetail']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});



 // Routes for Admin Role
 Route::middleware(['auth:sanctum', 'role_check:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Welcome to Admin Dashboard']);
    });

    // Category routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });

// skill

    Route::get('/skills',[CategoryController::class,'getSkill']);
    Route::post('/skills/store',[CategoryController::class,'storeSkill']);
    // Route::get('show/{id}',[CategoryController::class,'show']);
    Route::post('/skills/update',[CategoryController::class,'updateSkill']);
    Route::delete('/skills/delete/{id}',[CategoryController::class,'deleteSkill']);



    // Sub-Category routes
    Route::prefix('sub-categories')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index']);
        Route::post('/store', [SubCategoryController::class, 'store']);
        Route::get('/show/{id}', [SubCategoryController::class, 'show']);
        Route::post('/update', [SubCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [SubCategoryController::class, 'destroy']);
    });

    // Brand routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::post('/store', [BrandController::class, 'store']);
        Route::get('/show/{id}', [BrandController::class, 'show']);
        Route::post('/update', [BrandController::class, 'update']);
        Route::delete('/delete/{id}', [BrandController::class, 'destroy']);
    });

    // Unit routes
    Route::prefix('units')->group(function () {
        Route::get('/', [UnitController::class, 'index']);
        Route::post('/store', [UnitController::class, 'store']);
        Route::get('/show/{id}', [UnitController::class, 'show']);
        Route::post('/update', [UnitController::class, 'update']);
        Route::delete('/delete/{id}', [UnitController::class, 'destroy']);
    });


    // Products routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/show/{id}', [ProductController::class, 'show']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    });

});

// Routes for Vendor Role
Route::middleware(['auth:sanctum', 'role_check:vendor'])->group(function () {
    Route::get('/vendor/dashboard', function () {
        return response()->json(['message' => 'Welcome to Vendor Dashboard']);
    });
});

// Routes for Customer Role
Route::middleware(['auth:sanctum', 'role_check:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return response()->json(['message' => 'Welcome to Customer Dashboard']);
    });
});

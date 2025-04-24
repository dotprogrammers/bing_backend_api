<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Backend\Blood\BloodCategoryController;
use App\Http\Controllers\Api\Backend\Blood\BloodDonateController;
use App\Http\Controllers\Api\Backend\BookingController;
use App\Http\Controllers\Api\Backend\Brand\BrandController;
use App\Http\Controllers\Api\Backend\Category\CategoryController;
use App\Http\Controllers\Api\Backend\Jobs\JobCategoryController;
use App\Http\Controllers\Api\Backend\Education\EducationController;
use App\Http\Controllers\Api\Backend\Jobs\JobProfileController;
use App\Http\Controllers\Api\Backend\Products\ProductController;
use App\Http\Controllers\Api\Backend\Profile\ProfileController;
use App\Http\Controllers\Api\Frontend\UserController;
use App\Http\Controllers\Api\Frontend\UserRentController;
use App\Http\Controllers\Api\Frontend\FrontendController;
use App\Http\Controllers\Api\Backend\Rent\RentController;
use App\Http\Controllers\Api\Backend\Rent\RentCategoryController;
use App\Http\Controllers\Api\Backend\DashboardController;
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
Route::post('mobile-verify', [AuthController::class, 'mobileVerify']);
Route::post('login', [AuthController::class, 'login']);

// Frontend Routes
Route::get('get-categories', [FrontendController::class, 'categories']);
Route::get('skill-job', [FrontendController::class, 'skills']);
Route::get('get-brands', [FrontendController::class, 'brands']);
Route::get('job-categories', [FrontendController::class, 'jobCategories']);
Route::get('get-products', [FrontendController::class, 'products']);
Route::get('exchange-products', [FrontendController::class, 'exchangeProducts']);
Route::get('product-detail/{id}', [FrontendController::class, 'productDetail']);
Route::get('educations', [FrontendController::class, 'education']);
Route::get('blood-categories', [FrontendController::class, 'bloodCategories']);
Route::get('rent-categories', [FrontendController::class, 'rentCategories']);
Route::post('product-booking', [FrontendController::class, 'productBooking']);

Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('email/verification-notification', [AuthController::class, 'sendVerificationEmail']);

    // Jobs routes
    Route::prefix('job')->group(function () {
        Route::get('/', [JobProfileController::class, 'index']);
        Route::post('/store', [JobProfileController::class, 'storeOrUpdate']);
        Route::get('/show/{id}', [JobProfileController::class, 'show']);
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::post('/store', [ProfileController::class, 'storeOrUpdate']);
        Route::get('/show-profile', [ProfileController::class, 'show']);
    });

    // Rent routes
    Route::apiResource('rents', UserRentController::class)->except(['create', 'edit']);
    Route::prefix('rents')->controller(UserRentController::class)->group(function () {
        Route::get('/mark-as-favourite/{id}', 'markAsFavouriteRent');
        Route::get('/removed-as-favourite/{id}', 'removedFavouriteRent');
        Route::get('/favourite-list', 'rentList');
    });
    

    // Product Booking routes
    Route::prefix('booking')->group(function () {
        Route::get('/list', [BookingController::class, 'index']);
        Route::post('/store', [BookingController::class, 'store']);
        Route::get('/show/{id}', [BookingController::class, 'show']);
        Route::post('/update', [BookingController::class, 'update']);
        Route::delete('/delete/{id}', [BookingController::class, 'destroy']);
    });


    // Blood Donate routes
    Route::get('donate-blood', [BloodDonateController::class, 'index']);

    // User routes
    Route::prefix('user')->group(function () {
        Route::post('/save-token', [UserController::class, 'saveToken']);
    });
});

// Routes for Admin
Route::prefix('admin')->middleware(['auth:sanctum', 'role_check:admin'])->group(function () {
    Route::get('/dashboard', DashboardController::class);

    // Category routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/show/{id}', [CategoryController::class, 'show']);
        Route::post('/update', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });

    // Brand routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::post('/store', [BrandController::class, 'store']);
        Route::get('/show/{id}', [BrandController::class, 'show']);
        Route::post('/update', [BrandController::class, 'update']);
        Route::delete('/delete/{id}', [BrandController::class, 'destroy']);
    });


    // Products routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/show/{id}', [ProductController::class, 'show']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    });

    // Education routes
    Route::prefix('education')->group(function () {
        Route::get('/', [EducationController::class, 'index']);
        Route::post('/store', [EducationController::class, 'store']);
        Route::get('/show/{id}', [EducationController::class, 'show']);
        Route::post('/update', [EducationController::class, 'update']);
        Route::delete('/delete/{id}', [EducationController::class, 'destroy']);
    });

    // Job Category routes
    Route::prefix('job-category')->group(function () {
        Route::get('/', [JobCategoryController::class, 'index']);
        Route::post('/store', [JobCategoryController::class, 'store']);
        Route::get('/show/{id}', [JobCategoryController::class, 'show']);
        Route::post('/update', [JobCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [JobCategoryController::class, 'destroy']);
    });

    // Blood Category routes
    Route::prefix('blood-category')->group(function () {
        Route::get('/', [BloodCategoryController::class, 'index']);
        Route::post('/store', [BloodCategoryController::class, 'store']);
        Route::get('/show/{id}', [BloodCategoryController::class, 'show']);
        Route::post('/update', [BloodCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [BloodCategoryController::class, 'destroy']);
    });

    // Rent Category routes
    Route::prefix('rent-category')->group(function () {
        Route::get('/', [RentCategoryController::class, 'index']);
        Route::post('/store', [RentCategoryController::class, 'store']);
        Route::get('/show/{id}', [RentCategoryController::class, 'show']);
        Route::post('/update', [RentCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [RentCategoryController::class, 'destroy']);
    });

    // Admin Rent routes
    Route::apiResource('rents', RentController::class)->except(['create', 'store', 'edit']);

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

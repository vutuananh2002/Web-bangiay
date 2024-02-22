<?php

use App\Http\Controllers\Api\v1\Admin\AdminController;
use App\Http\Controllers\Api\v1\Admin\Auth\AuthController as AuthAdminController;
use App\Http\Controllers\Api\v1\Admin\AuthorizationController;
use App\Http\Controllers\Api\v1\Admin\UserController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\ColorController;
use App\Http\Controllers\Api\v1\Admin\CustomerController;
use App\Http\Controllers\Api\v1\Admin\ImageController;
use App\Http\Controllers\Api\v1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\v1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\v1\ManufactureController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\ReviewController;
use App\Http\Controllers\Api\v1\SizeController;
use App\Http\Controllers\Api\v1\TypeController;
use App\Http\Controllers\Api\v1\User\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\v1\Auth\VerifyForgotPasswordTokenController;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\PriceController;
use App\Http\Controllers\Api\v1\RatingController;
use App\Http\Controllers\Api\v1\Auth\ChangeEmailController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\ReportController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index']);
    });

    // Product Route
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ProductController::class, 'store']);
            Route::put('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'destroy']);
            Route::post('/add-image/{product}', [ProductController::class, 'addImage']);
            Route::post('/delete-image/{product}', [ProductController::class, 'deleteImage']);
        });
    });

    // Manufacture Route
    Route::prefix('manufactures')->group(function () {
        Route::get('/', [ManufactureController::class, 'index']);
        Route::get('/with-paginate', [ManufactureController::class, 'withPagination']);
        Route::get('/{manufacture}', [ManufactureController::class, 'show']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ManufactureController::class, 'store']);
            Route::put('/{manufacture}', [ManufactureController::class, 'update']);
            Route::delete('/{manufacture}', [ManufactureController::class, 'destroy']);
            Route::post('/add-logo/{manufacture}', [ManufactureController::class, 'addLogo']);
            Route::delete('/delete-logo/{manufacture}', [ManufactureController::class, 'deleteLogo']);
        });
    });

    // Category Route
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/with-paginate', [CategoryController::class, 'withPagination']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::get('/search/{name}', [CategoryController::class, 'search']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{category}', [CategoryController::class, 'update']);
            Route::delete('/{category}', [CategoryController::class, 'destroy']);
        });
    });

    // Color Route
    Route::prefix('colors')->group(function () {
        Route::get('/', [ColorController::class, 'index']);
        Route::get('/with-paginate', [ColorController::class, 'withPagination']);
        Route::get('/{color}', [ColorController::class, 'show']);
        Route::get('/search/{name}', [ColorController::class, 'search']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ColorController::class, 'store']);
            Route::put('/{color}', [ColorController::class, 'update']);
            Route::delete('/{color}', [ColorController::class, 'destroy']);
        });
    });

    // Type Route
    Route::prefix('types')->group(function () {
        Route::get('/', [TypeController::class, 'index']);
        Route::get('/{type}', [TypeController::class, 'show']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [TypeController::class, 'store']);
            Route::put('/{type}', [TypeController::class, 'update']);
            Route::delete('/{type}', [TypeController::class, 'destroy']);
        });
    });

    // Size Route
    Route::prefix('sizes')->group(function () {
        Route::get('/', [SizeController::class, 'index']);
        Route::get('/with-paginate', [SizeController::class, 'withPagination']);
        Route::get('/{size}', [SizeController::class, 'show']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [SizeController::class, 'store']);
            Route::put('/{size}', [SizeController::class, 'update']);
            Route::delete('/{size}', [SizeController::class, 'destroy']);
        });
    });

    // Image Route
    Route::prefix('images')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ImageController::class, 'index']);
        Route::post('/', [ImageController::class, 'store']);
        Route::delete('/{image}', [ImageController::class, 'destroy']);
        Route::post('/delete-all', [ImageController::class, 'deleteAll']);
        Route::post('/delete-many', [ImageController::class, 'deleteMany']);
    });

    // Review Route
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [ReviewController::class, 'store']);
            Route::put('/{review}', [ReviewController::class, 'update']);
            Route::delete('/{review}', [ReviewController::class, 'destroy']);
            Route::post('/delete-all', [ReviewController::class, 'deleteAll']);
            Route::post('/delete-many', [ReviewController::class, 'deleteMany']);
        });
    });

    // Auth Controller
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/current-user', [UserController::class, 'currentUser']);
            Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
        });
    });

    // Admin Route
    Route::prefix('admin')->group(function () {
        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthAdminController::class, 'login']);
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('/register', [AuthAdminController::class, 'register']);
                Route::post('/logout', [AuthAdminController::class, 'logout']);
                Route::post('/refresh-token', [AuthAdminController::class, 'refreshToken']);
            });
        });

        // Admin Profile
        Route::prefix('admin-profile')->middleware('auth:sanctum')->group(function () {
            Route::get('/', [AdminController::class, 'index']);
            Route::get('/{admin}', [AdminController::class, 'show']);
            Route::put('/{admin}', [AdminController::class, 'update']);
            Route::delete('/{admin}', [AdminController::class, 'destroy']);
        });

        // Authorization
        Route::prefix('authorization')->middleware('auth:sanctum')->group(function () {
            Route::get('/', [AuthorizationController::class, 'index']);
            Route::get('/{role}', [AuthorizationController::class, 'show']);
            Route::post('/create-role', [AuthorizationController::class, 'storeRole']);
            Route::post('/create-permission', [AuthorizationController::class, 'storePermission']);
            Route::post('/manage-role', [AuthorizationController::class, 'manageRole']);
            Route::post('/manage-permission', [AuthorizationController::class, 'managePermission']);
        });
    });

    // Customer Route
    Route::prefix('customers')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::get('/{customer}', [CustomerController::class, 'show']);
        Route::put('/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
    });

    // User Route
    Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
    Route::prefix('users')->middleware('auth:sanctum')->group(function () {
        Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
    });

    // Order Route
    Route::apiResource('orders', OrderController::class)->except('destroy', 'store')->middleware('auth:sanctum');
    Route::prefix('orders')->middleware('auth:sanctum')->group(function () {
        Route::post('/cancel/{order}', [OrderController::class, 'cancelOrder']);
        Route::post('/confirm/{order}', [OrderController::class, 'confirmReceivedOrder']);
    });

    // Cart Route
    Route::apiResource('carts', CartController::class)->except(['update', 'index'])->middleware('auth:sanctum');
    Route::prefix('carts')->middleware('auth:sanctum')->group(function () {
        Route::post('/add-product/{cart}', [CartController::class, 'addProduct']);
        Route::post('/remove-product/{cart}', [CartController::class, 'removeProduct']);
        Route::post('/checkout/{cart}', [CartController::class, 'checkout']);
    });

    // Verify Email
    Route::middleware('throttle:5,1')->get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->name('verification.verify');

    Route::middleware(['auth:sanctum', 'throttle:5,1'])->post('/email/verify/resend', [VerifyEmailController::class, 'resend'])
        ->name('verification.send');

    Route::prefix('password')->middleware('throttle:5,1')->group(function () {
        Route::post('forgot', ForgotPasswordController::class);
        Route::post('verify-token', VerifyForgotPasswordTokenController::class);
        Route::put('reset', ResetPasswordController::class);
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('change-password', ChangePasswordController::class);
        });
    });

    Route::prefix('email')->middleware('auth:sanctum')->group(function () {
        Route::post('/change-email', ChangeEmailController::class);
    });
    Route::middleware('throttle:5,1')->get('/email/verify-change/{id}/{hash}', [ChangeEmailController::class, 'verify'])
    ->name('email.verify-change');

    Route::prefix('prices')->group(function () {
        Route::get('/', [PriceController::class, 'index']);
    });

    Route::prefix('rates')->group(function () {
        Route::get('/', RatingController::class);
    });

    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/{banner}', [BannerController::class, 'show']);
            Route::post('/', [BannerController::class, 'store']);
            Route::put('/{banner}', [BannerController::class, 'update']);
            Route::delete('/{banner}', [BannerController::class, 'delete']);
        });
    });

    Route::prefix('reports')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
    });
});

<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Organization\OrganizationController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\RolePermission\PermissionController;
use App\Http\Controllers\Api\RolePermission\RoleController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'registration']);
    Route::put('/update/{id}', [AuthController::class, 'update']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
  
    Route::post('/auth/logout', [AuthController::class, 'logout']);
  

    Route::prefix('organization')->group(
        function () {
            Route::get('/', [OrganizationController::class, 'index']);
            Route::post('create', [OrganizationController::class, 'store']);
            Route::get('update/{id}', [OrganizationController::class, 'editById']);
            Route::put('update/{id}', [OrganizationController::class, 'update']);
            Route::delete('destroy/{id}', [OrganizationController::class, 'destroy']);
        }
    );
    Route::prefix('product')->group(
        function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('create', [ProductController::class, 'store']);
            Route::get('update/{id}', [ProductController::class, 'editById']);
            Route::put('update/{id}', [ProductController::class, 'update']);
            Route::delete('destroy/{id}', [ProductController::class, 'destroy']);
        }
    );

    Route::prefix('role')->group(
        function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::get('get-permission', [RoleController::class, 'getAllPermission']);
            Route::get('get-permission-group', [RoleController::class, 'getPermissionGroup']);
            Route::post('create', [RoleController::class, 'store']);
            Route::get('update/{id}', [RoleController::class, 'editById']);
            Route::put('update/{id}', [RoleController::class, 'update']);
            Route::delete('destroy/{id}', [RoleController::class, 'destroy']);
        }
    );

    Route::prefix('permission')->group(
        function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('create', [PermissionController::class, 'store']);
            Route::get('update/{id}', [PermissionController::class, 'editById']);
            Route::put('update/{id}', [PermissionController::class, 'update']);
            Route::delete('destroy/{id}', [PermissionController::class, 'destroy']);
        }
    );

    Route::prefix('user')->group(
        function () {
            Route::get('/auth-user', [UserController::class, 'getAuthenticateUser']);
            Route::get('/client', [UserController::class, 'getAllClient']);
            Route::get('/all-role', [UserController::class, 'getAllWithoutClient']);
            Route::post('create', [UserController::class, 'store']);
            Route::get('update/{id}', [UserController::class, 'editById']);
            Route::put('update/{id}', [UserController::class, 'update']);
            Route::delete('destroy/{id}', [UserController::class, 'destroy']);
            Route::post('/update-password', [UserController::class, 'passwordUpdate']);
        }
    );
});
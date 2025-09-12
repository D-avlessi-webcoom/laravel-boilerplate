<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user()->load('roles.permissions');
})->middleware('auth:sanctum');

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// Permission routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-permissions-by-group', [RolePermissionController::class, 'getPermissionsByGroup']);
    Route::get('/roles', [RolePermissionController::class, 'index']);
    Route::post('/roles', [RolePermissionController::class, 'store']);
    Route::get('/roles/{id}', [RolePermissionController::class, 'show']);
    Route::match(['put', 'patch'], '/roles/{id}', [RolePermissionController::class, 'update']);
    Route::delete('/roles/{id}', [RolePermissionController::class, 'destroy']);
    Route::match(['put', 'patch'], '/roles/{role}/permissions', [RolePermissionController::class, 'assignPermissions']);
});

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::match(['put', 'patch'], '/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

Route::apiResource('/users', App\Http\Controllers\API\UserController::class);

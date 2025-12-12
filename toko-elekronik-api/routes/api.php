<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Container\Attributes\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

route::post('/register', [AuthController::class, 'register'])->name('api.register');
route::post('/login', [AuthController::class, 'login'])->name('api.login');

route::apiResource('/products',ProductController::class);

route::middleware('auth:api')->group(function(){
    route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    route::get('/me', [AuthController::class, 'me'])->name('api.me');
    route::put('/update-profile',[AuthController::class,'updateProfile'])->name('api.updateProfile');
    route::put('/change-password',[AuthController::class,'changePassword'])->name('api.changePassword');

    route::apiResource('/categories',CategoriesController::class);
});

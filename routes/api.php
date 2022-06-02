<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::group([
    'middleware' => 'api',
], function ($router) {

    Route::post('login', [AuthController::class,'login'])->name('api.login');
    Route::post('logout', [AuthController::class,'logout'])->name('api.logout');
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('register', [AuthController::class,'register'])->name('api.register');
    //funciones crud
    Route::get('users', [UserController::class, 'getAll'])->name('api.getAll');
    Route::get('users/{id}', [UserController::class, 'getOne']);
    Route::post('users', [UserController::class, 'create'])->name('api.create')->middleware('rol');
    Route::post('user/{id}', [UserController::class, 'editImage']);
    Route::delete('user/{id}', [UserController::class, 'destroyImg']);
    Route::put('users/{id}', [UserController::class, 'edit'])->middleware('rol');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->middleware('rol');
});
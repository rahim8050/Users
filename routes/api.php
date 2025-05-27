<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
route::post('/register', [UserController::class, 'store'])->name('register');
route::post('/login', [UserController::class, 'login'])->name('login');
// Route::prefix('auth')->group(function () {
//     Route::post('/password/update', [UserController::class, 'updatePassword'])
//         ->middleware('auth:sanctum');
// });
route::post('/updatePassword', [UserController::class, 'updatePassword'])
    ->middleware('auth:sanctum')
    ->name('change-password');
route::patch('/update', [UserController::class, 'update'])
    ->middleware('auth:sanctum')
    ->name('update-profile');

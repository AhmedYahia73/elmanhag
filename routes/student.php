<?php
namespace Student;

use App\Http\Controllers\api\v1\student\LoginController;
use App\Http\Controllers\api\v1\student\SignupController;
use App\Http\Middleware\StudentMiddleware;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::controller(SignupController::class)->group(function () {
        Route::post('signup/create','store')->name('student.signup');
    });
    Route::controller(LoginController::class)->group(function () {
        Route::post('login','login')->name('login.index');
        Route::post('logout','logout')->name('logout')->middleware(['auth.student','auth:sanctum']);
    });
});
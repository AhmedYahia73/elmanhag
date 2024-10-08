<?php
namespace Parent;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\parent\childreen\ChildreenController;
use App\Http\Controllers\api\v1\parent\profile\ProfileController;
use App\Http\Controllers\api\v1\parent\notifications\NotificationController;
use App\Http\Controllers\api\v1\parent\subjects\SubjectController;

Route::middleware(['auth:sanctum', 'IsParent'])->group(function(){    
    // Start Childreen Module
    Route::prefix('childreen')->group(function() {
        Route::controller(ChildreenController::class)->group(function(){
            Route::get('/', 'show')->name('childreen.show');
            Route::put('/profile/{id}', 'child_profile')->name('childreen.child_profile');
        });
    });
    // Start Profile Module
    Route::prefix('profile')->group(function() {
        Route::controller(ProfileController::class)->group(function(){
            Route::put('/', 'modify')->name('profile.update');
        });
    });
    // Start Profile Module
    Route::prefix('subjects')->group(function() {
        Route::controller(SubjectController::class)->group(function(){
            Route::post('/', 'subjects')->name('subjects.subjects');
            Route::post('/progress', 'subjects_progress')->name('subjects.subjects_progress');
        });
    });
    // Start Notifications Module
    Route::prefix('notification')->group(function() {
        Route::controller(NotificationController::class)->group(function(){
            Route::post('/', 'show')->name('notification.show');
            Route::post('/seen', 'seen_notifications')->name('notification.seen');
        });
    });
});
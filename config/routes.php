<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\AreasController;
use App\Controllers\ProfileController;
use App\Controllers\ReinforceAreasController;
use Core\Router\Route;

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
    Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [AreasController::class, 'index'])->name('root');

    // Create
    Route::get('/my-areas/new', [AreasController::class, 'new'])->name('areas.new');
    Route::post('/my-areas', [AreasController::class, 'create'])->name('areas.create');

    // Retrieve
    Route::get('/my-areas', [AreasController::class, 'index'])->name('areas.index');
    Route::get('/my-areas/page/{page}', [AreasController::class, 'index'])->name('areas.paginate');
    Route::get('/my-areas/{id}', [AreasController::class, 'show'])->name('areas.show');

    // Update
    Route::get('/my-areas/{id}/edit', [AreasController::class, 'edit'])->name('areas.edit');
    Route::put('/my-areas/{id}', [AreasController::class, 'update'])->name('areas.update');

    // Delete
    Route::delete('/my-areas/{id}', [AreasController::class, 'destroy'])->name('areas.destroy');

    // Upload Image
    Route::post('/my-areas/{id}/upload-image', [AreasController::class, 'uploadImage'])->name('areas.uploadImage');

    // Logout
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Reinforce Areas    
    Route::get('/areas/supported', [ReinforceAreasController::class, 'supported'])
    ->name('reinforce.areas.supported');
    
    Route::post('/areas/{id}', [ReinforceAreasController::class, 'support'])
    ->name('reinforce.areas.create');
    Route::post(
        '/areas/{id}/stopped-supporting',
        [ReinforceAreasController::class, 'stoppedSupporting']
        )->name('reinforce.areas.stopped-supporting');
        
    Route::middleware('admin')->group(function () {
        Route::get('/areas', [ReinforceAreasController::class, 'index'])
            ->name('reinforce.areas');
        Route::get('/areas/page/{page}', [ReinforceAreasController::class, 'index'])
            ->name('reinforce.areas.paginate');
        Route::post('/areas/{id}/update-status', [ReinforceAreasController::class, 'updateStatus'])
            ->name('reinforce.areas.updateStatus');
    });
});

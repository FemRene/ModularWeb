<?php

use App\Controller\DashboardController;
use App\Controller\ModulesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::prefix('/dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, "getIndex"])->name('dashboard');
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::get('/', [ModulesController::class, 'showModules'])->name('list');
        Route::post('/', [ModulesController::class, 'upload'])->name('upload');
        Route::post('{name}/activate', [ModulesController::class, 'activate'])->name('activate');
        Route::post('{name}/deactivate', [ModulesController::class, 'deactivate'])->name('deactivate');
        Route::delete('{name}', [ModulesController::class, 'delete'])->name('delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Route::post('/dashboard/settings/toggle-registration', [SettingsController::class, 'toggleRegistration'])->name('admin.settings.registration');
    //Route::post('/dashboard/settings/save', [SettingsController::class, 'saveAllSettings'])->name('admin.settings.saveAll');
    Route::get('/dashboard/settings', [SettingsController::class, 'show'])->name('admin.settings');
    Route::post('/dashboard/settings', [SettingsController::class, 'save'])->name('admin.settings.save');

});

if (SettingsController::get('registration_enabled')) {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest')->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest');
}

require __DIR__.'/auth.php';

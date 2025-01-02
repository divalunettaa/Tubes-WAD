<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\PesanController;
use App\Http\Controllers\User\KatalogController;
use App\Http\Controllers\User\ArtikelController;
use App\Http\Controllers\User\FaqController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminFormController;
use App\Http\Controllers\Admin\AdminKatalogController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminArtikelController;
use App\Http\Controllers\Admin\ManageUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// User Routes
Route::middleware('auth', 'userMiddleware')->group(function(){
    
    Route::get('dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::get('pesan', [PesanController::class, 'index'])->name('pesan');
    Route::get('katalog', [KatalogController::class, 'index'])->name('katalog');
    Route::get('artikel', [ArtikelController::class, 'index'])->name('artikel');
    Route::get('faq', [FaqController::class, 'index'])->name('faq');


});

// Admin Routes
Route::middleware('auth', 'adminMiddleware')->group(function(){
    
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/form', [AdminFormController::class, 'index'])->name('admin.form');
    Route::get('/admin/katalog', [AdminKatalogController::class, 'index'])->name('admin.katalog');
    Route::get('/admin/artikel', [AdminArtikelController::class, 'index'])->name('admin.artikel');
    Route::get('/admin/faq', [AdminFaqController::class, 'index'])->name('admin.faq');
    Route::get('/admin/user', [ManageUserController::class, 'index'])->name('admin.user');
    Route::get('/admin/user/{id}/edit', [ManageUserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/{id}', [ManageUserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [ManageUserController::class, 'destroy'])->name('admin.user.destroy');
});
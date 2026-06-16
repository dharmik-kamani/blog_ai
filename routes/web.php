<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SaveController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sketch', [HomeController::class, 'sketch'])->name('home.sketch');
Route::get('/blog/{blog:slug}', [HomeController::class, 'show'])->name('blog.show');

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password/reset', [ProfileController::class, 'resetPassword'])->name('password.reset');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/saved-blogs', [HomeController::class, 'saved'])->name('blogs.saved');
    Route::post('/blog/{blog}/like', [LikeController::class, 'toggle'])->name('blog.like');
    Route::post('/blog/{blog}/save', [SaveController::class, 'toggle'])->name('blog.save');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/blogs', [AdminController::class, 'blogs'])->name('blogs');
    Route::get('/blogs/create', [AdminController::class, 'createBlog'])->name('blogs.create');
    Route::post('/blogs', [AdminController::class, 'storeBlog'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [AdminController::class, 'editBlog'])->name('blogs.edit');
    Route::put('/blogs/{blog}', [AdminController::class, 'updateBlog'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [AdminController::class, 'deleteBlog'])->name('blogs.delete');
});

require __DIR__.'/auth.php';

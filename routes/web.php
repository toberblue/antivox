<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

// Blog routes
Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/post/{post}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{tag:slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/search', [BlogController::class, 'search'])->name('blog.search');

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin routes (protected)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts');
    Route::get('/posts/create', [AdminController::class, 'create'])->name('create');
    Route::post('/posts', [AdminController::class, 'store'])->name('store');
    Route::get('/posts/{post}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/posts/{post}', [AdminController::class, 'update'])->name('update');
    Route::delete('/posts/{post}', [AdminController::class, 'destroy'])->name('destroy');
    Route::get('/posts/{post}/share', [AdminController::class, 'share'])->name('share');
    Route::post('/tags', [AdminController::class, 'createTag'])->name('tags.create');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

// Route::get('/', function () {
//     return view('layouts.app');
// });

//page routes
Route::get('/', [PageController::class, 'index'])->name('welcome');
Route::get('login', [PageController::class,'login'])->name( 'login.page');
Route::get('register', [PageController::class,'register'])->name('register.page');

//login and register routes
Route::post('login', [AuthController::class,'login'])->name( 'login');
Route::post('register', [AuthController::class,'register'])->name('register');

//auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //post routes
    Route::get('posts', [PostController::class, 'index'])->name('posts');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


});
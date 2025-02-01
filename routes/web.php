<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

//page routes
Route::get('/', [PageController::class, 'index'])->name('welcome');
Route::get('login', [PageController::class,'login'])->name( 'login.page');
Route::get('register', [PageController::class,'register'])->name('register.page');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

//login and register routes
Route::post('login', [AuthController::class,'login'])->name( 'login');
Route::post('register', [AuthController::class,'register'])->name('register');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/posts')->with('status', 'Email verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //post routes
    Route::get('posts', [PostController::class, 'index'])->name('posts');
    Route::get('create/post', [PostController::class,'create'])->name('create.post');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});
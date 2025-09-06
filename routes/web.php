<?php
/**
 * Front-end API for the Web.
 */

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('login', function(){
    return view('pages.auth.login');
});

Route::get('register', function(){
    return view('pages.auth.register');
});

Route::get('user/{user}', [UserController::class, 'show'])->name('user.view');
Route::get('user/{user}/edit', [UserController::class, 'edit'])->middleware('auth');
Route::patch('user/{user}/update', [UserController::class, 'update'])->middleware('auth');

require __DIR__.'/auth.php';
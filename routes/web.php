<?php
/**
 * Front-end API for the Web.
 */

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/login', function(){
    return view('pages.login');
});

require __DIR__.'/auth.php';
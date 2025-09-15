<?php
/**
 * Front-end API for the Web.
 */

use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
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
Route::get('user/{user}/edit', [UserController::class, 'edit'])->middleware(['auth']);
Route::patch('user/{user}/update', [UserController::class, 'update'])->middleware(['auth', 'verified']);
Route::get('user/{user}/delete', [UserController::class, 'delete'])->middleware(['auth', 'verified']);
Route::delete('user/{user}/destroy', [UserController::class, 'destroy'])->middleware(['auth', 'verified'])->name('user.destroy');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('driver/enroll', [DriverController::class, 'create']);
    Route::post('driver/enroll/submit', [DriverController::class, 'store']);
    Route::delete('driver/{driver}/leave', [DriverController::class, 'destroy']);
    Route::get('driver/{driver}/edit', [DriverController::class, 'edit']);
    Route::patch('driver/{driver}/update', [DriverController::class, 'update']);

    Route::get('vehicle/create', [VehicleController::class, 'create'])->name('vehicle.create');
});


Route::get('settings', function(){
    return view('pages.user.settings', [
        'driverAccount' => Auth::user()->getDriverAccount(),
    ]);
})->middleware(['auth'])->name('settings');



require __DIR__.'/auth.php';
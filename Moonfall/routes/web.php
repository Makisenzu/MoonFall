<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\userController;
use App\Http\Controllers\zoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users/userIndex');
});

Route::post('/userSignup', [userController::class, 'store'])->name('userStore');
Route::post('/user', [userController::class, 'login'])->name('userLogin');
Route::get('/adminIndex', [adminController::class, 'index'])->name('adminIndex');
Route::get('/admin/zone', [zoneController::class, 'index'])->name('adminZoneIndex');
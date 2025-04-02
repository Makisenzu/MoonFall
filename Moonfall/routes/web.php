<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\informationController;
use App\Http\Controllers\userController;
use App\Http\Controllers\zoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users/userIndex');
});

Route::post('/userSignup', [userController::class, 'store'])->name('userStore');
Route::post('/user', [userController::class, 'login'])->name('userLogin');
Route::get('/adminIndex', [adminController::class, 'index'])->name('adminIndex');
Route::get('/admin/zone', [zoneController::class, 'create'])->name('adminZoneIndex');
Route::get('/zones', [zoneController::class, 'index']);
Route::post('/admin/zone', [zoneController::class, 'store'])->name('adminZoneStore');
Route::get('/admin/news', [informationController::class, 'create'])->name('adminNewsCreate');
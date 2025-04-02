<?php

use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users/userIndex');
});

Route::post('/userSignup', [userController::class, 'store'])->name('userStore');
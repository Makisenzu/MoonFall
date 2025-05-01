<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\applicantController;
use App\Http\Controllers\informationController;
use App\Http\Controllers\userController;
use App\Http\Controllers\volunteerController;
use App\Http\Controllers\zoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('users/userIndex');
});

Route::post('/userSignup', [userController::class, 'store'])->name('userStore');
Route::post('/user', [userController::class, 'login'])->name('userLogin');
Route::get('/userDashboard/{id}', [userController::class, 'create'])->name('userDashboardCreate');
Route::get('/adminIndex', [adminController::class, 'index'])->name('adminIndex');
Route::get('/admin/zone', [zoneController::class, 'create'])->name('adminZoneIndex');
Route::get('/zones', [zoneController::class, 'index']);
Route::post('/admin/zone', [zoneController::class, 'store'])->name('adminZoneStore');
Route::get('/admin/news', [informationController::class, 'create'])->name('adminNewsCreate');

Route::get('/user/zones', [zoneController::class, 'userView'])->name('userViewZone');

Route::get('user/logout', [userController::class, 'logout'])->name('userLogout');
Route::post('admin/news', [informationController::class, 'store'])->name('adminStoreNews');
Route::delete('admin/news/{id}', [informationController::class, 'destroy'])->name('adminDeleteNews');

Route::get('admin/volunteer', [volunteerController::class, 'index'])->name('adminVolunteerIndex');

Route::get('user/applicant', [applicantController::class, 'index'])->name('userApplicant');

Route::get('user/profile/{id}', [userController::class, 'show'])->name('userProfile');
Route::put('user/update/{id}', [userController::class, 'update'])->name('userUpdate');

Route::post('user/applied/{id}', [volunteerController::class, 'store'])->name('volunteerStore');
Route::get('admin/applicants',[volunteerController::class, 'viewApplicants'])->name('viewApplicants');
Route::put('admin/applicant/approved/{id}', [volunteerController::class, 'update'])->name('approvedApplicant');
Route::put('admin/removeVolunteer/{id}', [volunteerController::class, 'removeVolunteer'])->name('removeVolunteer');
Route::put('admin/denied/{id}', [volunteerController::class, 'denied'])->name('applicationDenied');

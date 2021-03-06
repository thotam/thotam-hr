<?php

use Illuminate\Support\Facades\Route;
use Thotam\ThotamHr\Http\Controllers\HrController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web','auth', 'CheckAccount', 'CheckHr', 'CheckInfo'])->group(function () {

    //Route Admin
    Route::redirect('admin', '/', 301);
    Route::group(['prefix' => 'admin'], function () {

        //Route quản lý người dùng
        Route::redirect('member', '/', 301);
        Route::group(['prefix' => 'member'], function () {

            Route::get('hr',  [HrController::class, 'index'])->name('admin.member.hr');
        });

    });


});

Route::middleware(['web','CheckBrowser', 'auth', 'CheckAccount', 'CheckHr'])->group(function () {

    Route::get('info',  [HrController::class, 'info'])->name('hr.info');

});

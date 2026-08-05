<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [GoogleAuthController::class, 'showLogin'])->name('login');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', fn () => view('home'))->name('home');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    Route::get('/research-fee/payments', fn () => view('modules.placeholder', [
        'title' => 'จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย',
        'description' => 'เมนูย่อยภายใต้ค่าธรรมเนียมวิจัย',
    ]))->name('research-fee.payments');

    Route::get('/research-fee/import', fn () => view('modules.placeholder', [
        'title' => 'นำเข้าข้อมูลนักศึกษาจาก REG',
        'description' => 'เมนูย่อยภายใต้ค่าธรรมเนียมวิจัย',
    ]))->name('research-fee.import');

    Route::get('/users/permissions', [UserPermissionController::class, 'index'])->name('users.permissions');
    Route::post('/users/permissions/grant', [UserPermissionController::class, 'grant'])->name('users.permissions.grant');
    Route::post('/users/permissions/revoke', [UserPermissionController::class, 'revoke'])->name('users.permissions.revoke');

    Route::get('/late-exam/import', fn () => view('modules.placeholder', [
        'title' => 'นำเข้าข้อมูลนักศึกษาจาก REG',
        'description' => 'เมนูย่อยภายใต้รายงานการเข้าสอบสาย',
    ]))->name('late-exam.import');

    Route::get('/late-exam/record', fn () => view('modules.placeholder', [
        'title' => 'บันทึกการเข้าสอบช้า',
        'description' => 'เมนูย่อยภายใต้รายงานการเข้าสอบสาย',
    ]))->name('late-exam.record');

    Route::get('/late-exam/print', fn () => view('modules.placeholder', [
        'title' => 'พิมพ์แบบฟอร์มการเข้าสอบช้า',
        'description' => 'เมนูย่อยภายใต้รายงานการเข้าสอบสาย',
    ]))->name('late-exam.print');
});

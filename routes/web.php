<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LateExamSignerController;
use App\Http\Controllers\ResearchFeeImportController;
use App\Http\Controllers\ResearchFeePaymentController;
use App\Http\Controllers\ResearchFeeSummaryReportController;
use App\Http\Controllers\UserPermissionController;
use App\Models\Privilege;
use App\Services\DocumentSignerService;
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

Route::middleware(['auth', 'scireg.role'])->group(function () {
    Route::get('/home', fn () => view('home'))->name('home');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    Route::middleware('scireg.role:'.Privilege::LEVEL_SERVICE.','.Privilege::LEVEL_FINANCE)->group(function () {
        Route::get('/research-fee/payments', [ResearchFeePaymentController::class, 'index'])
            ->name('research-fee.payments');
        Route::patch('/research-fee/payments', [ResearchFeePaymentController::class, 'update'])
            ->name('research-fee.payments.update');
        Route::get('/research-fee/payments/report', [ResearchFeePaymentController::class, 'report'])
            ->name('research-fee.payments.report');
        Route::get('/research-fee/notices/student', [ResearchFeePaymentController::class, 'studentNotice'])
            ->name('research-fee.notice.student');
        Route::get('/research-fee/notices/sponsor', [ResearchFeePaymentController::class, 'sponsorNotice'])
            ->name('research-fee.notice.sponsor');
    });

    Route::middleware('scireg.role:'.Privilege::LEVEL_SERVICE)->group(function () {
        Route::get('/research-fee/import', [ResearchFeeImportController::class, 'index'])->name('research-fee.import');
        Route::post('/research-fee/import', [ResearchFeeImportController::class, 'store'])->name('research-fee.import.store');

        Route::get('/users/permissions', [UserPermissionController::class, 'index'])->name('users.permissions');
        Route::post('/users/permissions/grant', [UserPermissionController::class, 'grant'])->name('users.permissions.grant');
        Route::post('/users/permissions/revoke', [UserPermissionController::class, 'revoke'])->name('users.permissions.revoke');

        Route::get('/late-exam/import', fn () => view('modules.placeholder', [
            'title' => 'นำเข้าข้อมูลสอบสายจาก REG',
            'description' => 'เมนูย่อยภายใต้รายงานเข้าสอบสายนักศึกษา',
        ]))->name('late-exam.import');

        Route::get('/late-exam/record', fn () => view('modules.placeholder', [
            'title' => 'บันทึกการเข้าสอบช้า',
            'description' => 'เมนูย่อยภายใต้รายงานเข้าสอบสายนักศึกษา',
        ]))->name('late-exam.record');

        Route::get('/late-exam/print', function (DocumentSignerService $signers) {
            return view('late-exam.print', [
                'signer' => $signers->activeSigner(),
            ]);
        })->name('late-exam.print');

        Route::get('/late-exam/signers', [LateExamSignerController::class, 'index'])
            ->name('late-exam.signers');
        Route::put('/late-exam/signers', [LateExamSignerController::class, 'update'])
            ->name('late-exam.signers.update');

        Route::get('/late-exam/summary', fn () => view('modules.placeholder', [
            'title' => 'รายงานสรุปการเข้าสอบสาย',
            'description' => 'เมนูย่อยภายใต้รายงานเข้าสอบสายนักศึกษา',
        ]))->name('late-exam.summary');
    });

    Route::middleware('scireg.role:'.Privilege::LEVEL_SERVICE.','.Privilege::LEVEL_DEPARTMENT)->group(function () {
        Route::get('/research-fee/summary', [ResearchFeeSummaryReportController::class, 'index'])
            ->name('research-fee.summary');
        Route::get('/research-fee/summary/export', [ResearchFeeSummaryReportController::class, 'export'])
            ->name('research-fee.summary.export');
    });
});

<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\LateExamController;
use App\Http\Controllers\LateExamSignerController;
use App\Http\Controllers\LateExamTermSettingController;
use App\Http\Controllers\ResearchFeeImportController;
use App\Http\Controllers\ResearchFeePaymentController;
use App\Http\Controllers\ResearchFeeSummaryReportController;
use App\Http\Controllers\UserPermissionController;
use App\Models\Privilege;
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

Route::middleware(['auth', 'scireg.role', 'scireg.audit'])->group(function () {
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

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        Route::get('/late-exam/import', [LateExamController::class, 'importIndex'])->name('late-exam.import');
        Route::post('/late-exam/import', [LateExamController::class, 'importStore'])->name('late-exam.import.store');

        Route::get('/late-exam/record', [LateExamController::class, 'recordIndex'])->name('late-exam.record');
        Route::get('/late-exam/record/lookup', [LateExamController::class, 'lookup'])->name('late-exam.record.lookup');
        Route::get('/late-exam/record/courses', [LateExamController::class, 'lookupCourse'])->name('late-exam.record.courses');
        Route::get('/late-exam/record/departments', [LateExamController::class, 'lookupDepartment'])->name('late-exam.record.departments');
        Route::post('/late-exam/record', [LateExamController::class, 'recordStore'])->name('late-exam.record.store');

        Route::get('/late-exam/print', [LateExamController::class, 'printIndex'])->name('late-exam.print');
        Route::get('/late-exam/print/{id}', [LateExamController::class, 'printShow'])
            ->whereNumber('id')
            ->name('late-exam.print.show');
        Route::post('/late-exam/print/{id}/delete', [LateExamController::class, 'printDestroy'])
            ->whereNumber('id')
            ->name('late-exam.print.destroy');

        Route::get('/late-exam/signers', [LateExamSignerController::class, 'index'])
            ->name('late-exam.signers');
        Route::put('/late-exam/signers', [LateExamSignerController::class, 'update'])
            ->name('late-exam.signers.update');

        Route::get('/late-exam/summary', [LateExamController::class, 'summaryIndex'])->name('late-exam.summary');
        Route::get('/late-exam/summary/export', [LateExamController::class, 'summaryExport'])
            ->name('late-exam.summary.export');

        Route::get('/late-exam/term-setting', [LateExamTermSettingController::class, 'index'])
            ->name('late-exam.term-setting');
        Route::put('/late-exam/term-setting', [LateExamTermSettingController::class, 'update'])
            ->name('late-exam.term-setting.update');
    });

    Route::middleware('scireg.role:'.Privilege::LEVEL_SERVICE.','.Privilege::LEVEL_DEPARTMENT)->group(function () {
        Route::get('/research-fee/summary', [ResearchFeeSummaryReportController::class, 'index'])
            ->name('research-fee.summary');
        Route::get('/research-fee/summary/export', [ResearchFeeSummaryReportController::class, 'export'])
            ->name('research-fee.summary.export');
    });
});

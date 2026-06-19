<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\FaceExtractController;
use App\Http\Controllers\IzinController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/', [AbsensiController::class, 'index'])->name('index');
    Route::post('/', [AbsensiController::class, 'store'])->name('store');
    Route::get('/mahasiswas', [AbsensiController::class, 'getMahasiswas'])->name('mahasiswas');
    Route::get('/lokasi', [AbsensiController::class, 'getLokasi'])->name('lokasi');
});

Route::prefix('izin')->name('izin.')->group(function () {
    Route::get('/', [IzinController::class, 'index'])->name('index');
    Route::post('/', [IzinController::class, 'store'])->name('store');
});

Route::middleware('auth')->prefix('admin/extract-faces')->name('admin.extract-faces.')->group(function () {
    Route::post('/save', [FaceExtractController::class, 'save'])->name('save');
    Route::post('/upload', [FaceExtractController::class, 'upload'])->name('upload');
    Route::post('/sync', [FaceExtractController::class, 'sync'])->name('sync');
    Route::post('/delete', [FaceExtractController::class, 'deletePhoto'])->name('delete');
});

Route::middleware('auth')->get('/download/{path}', function (string $path) {
    $paths = [
        public_path('storage/' . $path),
        storage_path('app/public/' . $path),
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            return response()->download($file);
        }
    }
    abort(404);
})->where('path', '.*')->name('download.file');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotForm'])
        ->name('forgot-password');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])
        ->name('forgot-password.send');
    Route::get('/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyOtpForm'])
        ->name('verify-otp.form');
    Route::post('/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])
        ->name('verify-otp');
    Route::get('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])
        ->name('reset-password.form');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])
        ->name('reset-password');
});

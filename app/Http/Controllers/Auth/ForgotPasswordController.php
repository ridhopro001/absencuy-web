<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordOtp::where('email', $user->email)->where('used', false)->update(['used' => true]);

        PasswordOtp::create([
            'email' => $user->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(2),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Throwable $e) {
            Log::error('Gagal kirim OTP: ' . $e->getMessage());
            return back()->with('error', 'Maaf koneksi Anda sedang bermasalah, tidak bisa mengirim kode OTP.');
        }

        session(['otp_email' => $user->email]);

        return redirect()->route('auth.verify-otp.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyOtpForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('auth.forgot-password')->with('error', 'Silakan masukkan email terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('auth.forgot-password')->with('error', 'Sesi habis. Silakan ulangi dari awal.');
        }

        $otpRecord = PasswordOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa (2 menit). Silakan kirim ulang OTP.');
        }

        $otpRecord->update(['used' => true]);

        session(['otp_verified' => true]);

        return redirect()->route('auth.reset-password.form')->with('success', 'OTP terverifikasi. Silakan buat password baru.');
    }

    public function showResetForm()
    {
        if (!session('otp_email') || !session('otp_verified')) {
            return redirect()->route('auth.forgot-password')->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('otp_email');

        if (!$email || !session('otp_verified')) {
            return redirect()->route('auth.forgot-password')->with('error', 'Sesi habis. Silakan ulangi dari awal.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('auth.forgot-password')->with('error', 'User tidak ditemukan.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget(['otp_email', 'otp_verified']);

        return redirect('/admin/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}

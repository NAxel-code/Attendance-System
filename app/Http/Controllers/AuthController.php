<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Batas maksimal percobaan login sebelum akun/IP di-throttle.
     */
    protected const MAX_LOGIN_ATTEMPTS = 5;

    /**
     * Durasi lockout (dalam detik) jika melebihi batas percobaan.
     */
    protected const DECAY_SECONDS = 60;

    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login dengan proteksi Brute Force & Session Fixation.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Key unik rate limiting berdasarkan email dan IP pengunjung
        $throttleKey = $this->throttleKey($request);

        // 1. Cek apakah limit percobaan login telah terlampaui (Brute Force Protection)
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login gagal. Akses dibatasi sementara demi keamanan. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // 2. Percobaan Autentikasi Menggunakan Prepared Statement (Bawaan PDO/Eloquent)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Bersihkan tracker rate limit jika login berhasil
            RateLimiter::clear($throttleKey);

            // Lindungi dari Session Fixation dengan regenerate session ID
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // 3. Catat percobaan gagal dan kurangi kuota percobaan
        RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

        $remainingAttempts = RateLimiter::remaining($throttleKey, self::MAX_LOGIN_ATTEMPTS);

        $errorMessage = 'Email atau password yang Anda masukkan salah.';
        if ($remainingAttempts > 0) {
            $errorMessage .= " Sisa percobaan: {$remainingAttempts} kali.";
        }

        return back()
            ->withErrors(['email' => $errorMessage])
            ->onlyInput('email');
    }

    /**
     * Logout user, musnahkan sesi, dan perbarui CSRF token.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidasi data sesi aktif di server
        $request->session()->invalidate();

        // Regenerasi CSRF token untuk mencegah serangan CSRF pada sesi berikutnya
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout dengan aman.');
    }

    /**
     * Generate throttle key unik untuk request login (email | IP).
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
    }
}

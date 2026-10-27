<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private function validateTurnstile($token) {
        $secret = env('TURNSTILE_SECRET_KEY');
        // Bypass in development if not configured or default placeholder
        if (empty($secret) || str_starts_with($secret, '0x4AAAAAAA')) {
            return true;
        }
        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::withOptions([
                'verify' => config('services.ssl.ca_bundle', true),
            ])->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);
            return $response->json('success') === true;
        } catch (\Throwable $e) {
            Log::error('Turnstile verification error: ' . $e->getMessage());
            return false;
        }
    }

    // --- REGISTER ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        // 1. Validasi Turnstile
        if (!$this->validateTurnstile($request->input('cf-turnstile-response'))) {
            return back()->withErrors(['email' => 'Validasi keamanan (Turnstile) gagal.'])->withInput();
        }

        // 2. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 3. Simpan User Baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // --- LOGIN ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        // 1. Validasi Turnstile
        if (!$this->validateTurnstile($request->input('cf-turnstile-response'))) {
            return back()->withErrors(['email' => 'Validasi keamanan (Turnstile) gagal.'])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- LOGIKA REDIRECT BERDASARKAN ROLE ---
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika user biasa
            return redirect()->route('chat.index')->with('show_telegram_popup', true);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // --- GOOGLE OAUTH ---
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                Auth::login($user);
            } else {
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser);
            }
            return redirect()->route('chat.index')->with('show_telegram_popup', true);
        } catch (\Throwable $e) {
            Log::error('Google OAuth callback error', ['msg' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke Halaman Welcome setelah Logout
        return redirect('/');
    }
}

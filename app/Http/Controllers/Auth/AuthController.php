<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard masing-masing
        if (auth()->check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek status aktif user
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi admin.'])->onlyInput('email');
            }

            return $this->redirectByRole();
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    private function redirectByRole()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('tutor')) {
            return redirect()->route('tutor.dashboard');
        }

        // Role tidak dikenali
        Auth::logout();
        return redirect()->route('login')->withErrors(['email' => 'Role tidak dikenali. Hubungi admin.']);
    }
}

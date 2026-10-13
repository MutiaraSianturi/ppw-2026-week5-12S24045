<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Auth::attempt = cek email + password (password dibandingkan dengan hash di database)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // WAJIB: ganti ID sesi baru setelah login (anti Session Fixation)
            $request->session()->regenerate();

            // Simpan data di SESSION (sisi server)
            session(['login_timestamp' => now()->format('d M Y, H:i:s') . ' WIB']);

            // Simpan COOKIE (sisi browser), 10080 menit = 7 hari
            Cookie::queue('del_last_user_email', $request->email, 10080);

            // intended = balik ke halaman yang tadi mau dibuka sebelum dilempar ke login
            return redirect()->intended(route('buku.index'))
                ->with('success', 'Login berhasil, sesi Anda telah aktif.');
        }

        // Gagal: balik ke form, isi ulang email saja (password jangan)
        return back()->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();                          // keluarkan user
        $request->session()->invalidate();       // hapus seluruh data sesi di server
        $request->session()->regenerateToken();  // ganti token CSRF

        return redirect()->route('login')
            ->with('success', 'Anda sudah logout, sesi dihapus dengan aman.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    
public function register(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'username' => 'required|string|max:50|alpha_dash|unique:users,username',
        'nis' => 'required|string|exists:siswa,nis',
        'no_hp' => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',
    ], [
        'username.unique' => 'Username sudah digunakan.',
        'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
        'nis.exists' => 'NIS tidak ditemukan pada data siswa.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

    $siswa = Siswa::where('nis', $request->nis)->first();

    if (!$siswa) {
        return back()->withErrors([
            'nis' => 'NIS tidak ditemukan pada data siswa.',
        ])->withInput($request->except('password', 'password_confirmation'));
    }

    // Periksa apakah nama sesuai dengan data siswa.
    $namaInput = mb_strtolower(trim($request->nama_lengkap));
    $namaDatabase = mb_strtolower(trim($siswa->nama_lengkap));

    if ($namaInput !== $namaDatabase) {
        return back()->withErrors([
            'nama_lengkap' => 'Nama lengkap tidak sesuai dengan data siswa.',
        ])->withInput($request->except('password', 'password_confirmation'));
    }

    // Pastikan NIS belum memiliki akun.
    if ($siswa->user_id !== null) {
        return back()->withErrors([
            'nis' => 'NIS ini sudah memiliki akun.',
        ])->withInput($request->except('password', 'password_confirmation'));
    }

    DB::transaction(function () use ($request, $siswa) {
        $user = User::create([
            'name' => $siswa->nama_lengkap,
            'username' => $request->username,
            'email' => null,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // Hubungkan akun dengan data siswa dan simpan nomor HP.
        $siswa->user_id = $user->id;
        $siswa->no_hp = $request->no_hp;
        $siswa->save();
    });

    return redirect()->route('login')
        ->with('success', 'Registrasi berhasil. Silakan login menggunakan username dan password.');
}


    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->login;

        if (str_contains($login, '@')) {
            // Login admin menggunakan email
            $berhasil = Auth::attempt([
                'email' => $login,
                'password' => $request->password,
                'role' => 'admin',
            ]);
        } else {
            // Login siswa menggunakan username
            $berhasil = Auth::attempt([
                'username' => $login,
                'password' => $request->password,
                'role' => 'siswa',
            ]);
        }

        if ($berhasil) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('siswa.dashboard');
        }

        return back()->withErrors([
            'login' => 'Username/email atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

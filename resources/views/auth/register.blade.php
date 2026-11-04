@extends('layouts.auth')
@section('title', 'Registrasi Siswa')
@section('side_title')Buat akun siswa dan mulai <em>ajukan layanan</em>@endsection
@section('side_text', 'Daftarkan akunmu menggunakan NIS yang sudah terdata di sekolah. Setelah itu kamu bisa mengajukan peminjaman rapor, legalisir, dan pengambilan dokumen.')

@section('content')
<h2>Registrasi Siswa</h2>
<p class="sub">Isi data sesuai yang tercatat di sekolah agar akunmu dapat diverifikasi.</p>

@if ($errors->any())
    <div class="alert alert-error"><x-icon name="alert" :size="18" /><div><strong>Periksa kembali data:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif

<form action="{{ route('register.process') }}" method="POST" data-once>
    @csrf
    <div class="field">
        <label for="nama_lengkap">Nama Lengkap</label>
        <div class="input-icon"><x-icon name="user" :size="18" />
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Sesuai data sekolah" maxlength="255" autocomplete="name" required>
        </div>
    </div>
    <div class="form-grid">
        <div class="field">
            <label for="username">Username</label>
            <div class="input-icon"><x-icon name="id" :size="18" />
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Buat username" maxlength="50" autocomplete="username" required>
            </div>
        </div>
        <div class="field">
            <label for="nis">NIS</label>
            <div class="input-icon"><x-icon name="file" :size="18" />
                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" placeholder="NIS terdaftar" autocomplete="off" required>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="no_hp">Nomor HP</label>
        <div class="input-icon"><x-icon name="phone" :size="18" />
            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" maxlength="20" autocomplete="tel" required>
        </div>
    </div>
    <div class="form-grid">
        <div class="field">
            <label for="password">Password</label>
            <div class="input-icon has-toggle"><x-icon name="lock" :size="18" />
                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" autocomplete="new-password" required>
                <button type="button" class="toggle-pass" data-target="password" aria-label="Tampilkan password">
                    <span class="eye-on"><x-icon name="eye" :size="18" /></span><span class="eye-off" hidden><x-icon name="eye-off" :size="18" /></span>
                </button>
            </div>
        </div>
        <div class="field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <div class="input-icon has-toggle"><x-icon name="lock" :size="18" />
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" minlength="8" autocomplete="new-password" required>
                <button type="button" class="toggle-pass" data-target="password_confirmation" aria-label="Tampilkan password">
                    <span class="eye-on"><x-icon name="eye" :size="18" /></span><span class="eye-off" hidden><x-icon name="eye-off" :size="18" /></span>
                </button>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top:6px">Daftar Akun <x-icon name="arrow-right" :size="18" /></button>
</form>

<p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
@endsection

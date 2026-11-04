@extends('layouts.auth')
@section('title', 'Login')
@section('side_title')Selamat datang kembali di <em>SIMDA</em>@endsection
@section('side_text', 'Masuk untuk mengakses Sistem Manajemen Dokumen Akademik — ajukan layanan, pantau status, dan kelola dokumen sekolah dengan mudah.')

@section('content')
<h2>Masuk ke akun</h2>
<p class="sub">Gunakan username (siswa) atau email (admin) untuk melanjutkan.</p>

@if (session('success'))
    <div class="alert alert-success"><x-icon name="check-circle" :size="18" /><div>{{ session('success') }}</div></div>
@endif
@if ($errors->any())
    <div class="alert alert-error"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
@endif

<form action="{{ route('login.process') }}" method="POST" data-once>
    @csrf
    <div class="field">
        <label for="login">Username atau Email</label>
        <div class="input-icon">
            <x-icon name="user" :size="18" />
            <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Masukkan username atau email" autocomplete="username" required autofocus>
        </div>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <div class="input-icon has-toggle">
            <x-icon name="lock" :size="18" />
            <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
            <button type="button" class="toggle-pass" data-target="password" aria-label="Tampilkan password">
                <span class="eye-on"><x-icon name="eye" :size="18" /></span>
                <span class="eye-off" hidden><x-icon name="eye-off" :size="18" /></span>
            </button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top:6px">Masuk <x-icon name="arrow-right" :size="18" /></button>
</form>

<p class="auth-switch">Belum punya akun siswa? <a href="{{ route('register') }}">Registrasi</a></p>
@endsection

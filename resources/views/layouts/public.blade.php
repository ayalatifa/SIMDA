<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title', 'SIMDA') — Sistem Manajemen Dokumen Akademik</title>
    <meta name="description" content="@yield('description', 'SIMDA membantu siswa mengajukan peminjaman rapor, legalisir dokumen, dan pengambilan dokumen akademik secara online.')">
</head>
<body>
@php
    $dashboardUrl = auth()->check()
        ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('siswa.dashboard'))
        : null;
@endphp
<header class="site-header">
    <div class="container wrap">
        <a href="{{ route('home') }}" class="brand" aria-label="SIMDA — Beranda">
            <img src="{{ asset('img/logo-simda.svg') }}" alt="Logo SIMDA">
            <span class="brand-text"><strong>SIMDA</strong><small>Sistem Manajemen Dokumen Akademik</small></span>
        </a>

        <nav class="site-nav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('informasi') }}" class="{{ request()->routeIs('informasi') ? 'active' : '' }}">Informasi</a>
            <a href="{{ route('layanan') }}" class="{{ request()->routeIs('layanan') ? 'active' : '' }}">Layanan</a>
        </nav>

        <div class="site-actions">
            @if ($dashboardUrl)
                <a href="{{ $dashboardUrl }}" class="btn btn-primary btn-sm btn-login"><x-icon name="home" :size="16" /> Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-login"><x-icon name="user" :size="16" /> Login / Register</a>
            @endif
            <button class="nav-toggle" id="navToggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobilePanel">
                <x-icon name="menu" :size="22" />
            </button>
        </div>
    </div>
    <div class="mobile-panel" id="mobilePanel">
        <div class="inner">
            <a class="nl {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="nl {{ request()->routeIs('informasi') ? 'active' : '' }}" href="{{ route('informasi') }}">Informasi</a>
            <a class="nl {{ request()->routeIs('layanan') ? 'active' : '' }}" href="{{ route('layanan') }}">Layanan</a>
            @if ($dashboardUrl)
                <a class="btn btn-primary btn-block" href="{{ $dashboardUrl }}">Dashboard</a>
            @else
                <a class="btn btn-primary btn-block" href="{{ route('login') }}">Login / Register</a>
            @endif
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="cols">
            <div>
                <span class="brand">
                    <img src="{{ asset('img/logo-simda.svg') }}" alt="" width="42" height="42">
                    <span class="brand-text"><strong>SIMDA</strong><small>Sistem Manajemen Dokumen Akademik</small></span>
                </span>
                <p>Platform layanan dokumen akademik sekolah: peminjaman rapor, legalisir, dan pengambilan dokumen dalam satu tempat.</p>
            </div>
            <div>
                <h4>Jelajahi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('informasi') }}">Informasi &amp; Tata Cara</a>
                <a href="{{ route('layanan') }}">Layanan</a>
            </div>
            <div>
                <h4>Akun</h4>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Registrasi Siswa</a>
            </div>
        </div>
        <div class="copy">© {{ date('Y') }} SIMDA SMK · Sistem Manajemen Dokumen Akademik</div>
    </div>
</footer>
<script src="{{ asset('js/simda.js') }}?v=2" defer></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title') — SIMDA</title>
    @stack('styles')
</head>
<body>
@php
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $halaman = request('halaman', 'dashboard');
    $pendingCount = $isAdmin ? \App\Models\Pengajuan::where('status', 'Pending')->count() : 0;
    $initial = strtoupper(mb_substr($user->name ?? ($isAdmin ? 'A' : 'S'), 0, 1));
@endphp

<div class="app">
    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar" id="sidebar" aria-label="Menu utama">
        <a href="{{ $isAdmin ? route('admin.dashboard') : route('siswa.dashboard') }}" class="brand">
            <img src="{{ asset('img/logo-simda.svg') }}" alt="Logo SIMDA">
            <span class="brand-text"><strong>SIMDA SMK</strong><small>Dokumen Akademik</small></span>
        </a>

        <div class="side-role"><span class="dot"></span> {{ $isAdmin ? 'Administrator Tata Usaha' : 'Portal Siswa' }}</div>

        @if ($isAdmin)
            <div class="menu-title">Menu</div>
            <nav class="menu">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><x-icon name="home" /> Dashboard</a>
                <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}"><x-icon name="users" /> Data Siswa</a>
                <a href="{{ route('admin.dokumen.index') }}" class="{{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}"><x-icon name="folder" /> Dokumen Akademik</a>
                <a href="{{ route('admin.pengajuan.index') }}" class="{{ request()->routeIs('admin.pengajuan.*') ? 'active' : '' }}"><x-icon name="file" /> Pengajuan Layanan</a>
                <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}"><x-icon name="chart" /> Laporan &amp; Rekap</a>
            </nav>
        @else
            <div class="menu-title">Menu</div>
            <nav class="menu">
                <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') && $halaman !== 'riwayat' ? 'active' : '' }}"><x-icon name="home" /> Dashboard</a>
                <a href="{{ route('siswa.dashboard', ['halaman' => 'riwayat']) }}" class="{{ request()->routeIs('siswa.dashboard') && $halaman === 'riwayat' ? 'active' : '' }}"><x-icon name="clock" /> Riwayat Pengajuan</a>
                <a href="{{ route('siswa.dokumen') }}" class="{{ request()->routeIs('siswa.dokumen') ? 'active' : '' }}"><x-icon name="folder" /> Dokumen Saya</a>
            </nav>
        @endif

        <div class="side-foot">
            <form action="{{ route('logout') }}" method="POST" class="menu">
                @csrf
                <button type="submit" class="menu-link"><x-icon name="logout" /> Keluar</button>
            </form>
        </div>
    </aside>
    <div class="overlay" id="overlay"></div>

    {{-- ============ MAIN ============ --}}
    <div class="main">
        <header class="topbar">
            <button type="button" class="burger" id="burger" aria-label="Buka menu"><x-icon name="menu" :size="22" /></button>
            <div class="crumb">
                <small>{{ $isAdmin ? 'Panel Admin' : 'Portal Siswa' }}</small>
                <strong>@yield('title')</strong>
            </div>
            <div class="spacer"></div>
            <span class="ta-chip"><x-icon name="calendar" :size="15" /> TA 2026/2027</span>

            @if ($isAdmin)
                <button type="button" id="notificationLink" data-open="#notifModal"
                   data-endpoint="{{ route('admin.notifikasi.pengajuan') }}"
                   data-refresh="{{ route('admin.pengajuan.konfirmasi') }}"
                   class="icon-btn {{ $pendingCount > 0 ? 'has-notif' : '' }}" title="Pengajuan menunggu konfirmasi" aria-label="Pengajuan menunggu konfirmasi">
                    <x-icon name="bell" :size="21" />
                    <span id="notificationBadge" class="notif-badge" @if(!$pendingCount) hidden @endif>{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                </button>
            @endif

            <div class="profile" id="profile">
                <button type="button" class="profile-btn" id="profileBtn" aria-expanded="false" aria-haspopup="true">
                    <span class="avatar">{{ $initial }}</span>
                    <span class="nm">{{ $user->name ?? ($isAdmin ? 'Admin TU' : 'Siswa') }}</span>
                    <x-icon name="chevron-down" :size="16" />
                </button>
                <div class="profile-pop" role="menu">
                    <div class="pop-head">
                        <span class="avatar">{{ $initial }}</span>
                        <div>
                            <h4>{{ $user->name ?? ($isAdmin ? 'Admin TU' : 'Siswa') }}</h4>
                            <p>{{ $isAdmin ? 'Administrator Tata Usaha' : 'Siswa SIMDA SMK' }}</p>
                            <p>{{ $isAdmin ? $user->email : ($user->username ?? $user->email ?? 'Akun siswa') }}</p>
                        </div>
                    </div>
                    @if ($isAdmin)
                        <a class="pop-link" href="{{ route('admin.profil') }}"><x-icon name="user" :size="18" /> Profil Saya</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="pop-link danger"><x-icon name="logout" :size="18" /> Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="content">
            @if (session('success'))
                <div class="toast" role="status"><x-icon name="check-circle" :size="20" /> {{ session('success') }}</div>
            @endif
            @yield('content')
        </div>
        <footer class="app-footer">SIMDA SMK · Sistem Manajemen Dokumen Akademik</footer>
    </div>
</div>

@if ($isAdmin)
    @include('partials.notif-modal')
@endif

@stack('modals')

{{-- Modal konfirmasi hapus --}}
<div class="modal" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="modal-box">
        <div class="ico"><x-icon name="trash" :size="26" /></div>
        <h3 id="confirmTitle">Konfirmasi Penghapusan</h3>
        <p id="confirmText">Yakin ingin menghapus data ini?</p>
        <div class="acts">
            <button type="button" class="btn btn-secondary" id="confirmNo" data-close>Batal</button>
            <button type="button" class="btn btn-danger" id="confirmYes">Ya, Hapus</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/simda.js') }}?v=2" defer></script>
@stack('scripts')
</body>
</html>

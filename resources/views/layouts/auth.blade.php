<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
    <title>@yield('title') — SIMDA</title>
</head>
<body>
<div class="auth">
    <aside class="auth-side">
        <a href="{{ route('home') }}" class="brand" aria-label="Kembali ke beranda SIMDA">
            <img src="{{ asset('img/logo-simda.svg') }}" alt="Logo SIMDA">
            <span class="brand-text"><strong>SIMDA</strong><small>Sistem Manajemen Dokumen Akademik</small></span>
        </a>
        <div>
            <h1>@yield('side_title')</h1>
            <p class="lead">@yield('side_text')</p>
            <img class="art" src="{{ asset('img/info.svg') }}" alt="Ilustrasi aplikasi SIMDA di ponsel">
            <div class="points">
                <div><x-icon name="check-circle" :size="18" /> Ajukan rapor, legalisir &amp; dokumen secara online</div>
                <div><x-icon name="check-circle" :size="18" /> Pantau status pengajuan kapan saja</div>
                <div><x-icon name="check-circle" :size="18" /> Data aman dan dikelola Tata Usaha</div>
            </div>
        </div>
        <div class="legal">© {{ date('Y') }} SIMDA SMK</div>
    </aside>

    <section class="auth-main">
        <div class="auth-box">
            <a href="{{ route('home') }}" class="brand auth-mobile-brand">
                <img src="{{ asset('img/logo-simda.svg') }}" alt="" width="42" height="42">
                <span class="brand-text"><strong>SIMDA</strong><small>Sistem Manajemen Dokumen Akademik</small></span>
            </a>
            <a href="{{ route('home') }}" class="auth-back"><x-icon name="arrow-left" :size="16" /> Kembali ke beranda</a>
            @yield('content')
        </div>
    </section>
</div>
<script src="{{ asset('js/simda.js') }}?v=2" defer></script>
</body>
</html>

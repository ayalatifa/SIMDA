@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
<section class="hero">
    <div class="container">
        <div>
            <span class="eyebrow"><span class="dot"></span> Layanan dokumen akademik online</span>
            <h1>Urus dokumen sekolah, <em>tanpa antre</em> dan tanpa ribet.</h1>
            <p class="lead">SIMDA adalah Sistem Manajemen Dokumen Akademik yang membantu siswa mengajukan peminjaman rapor, legalisir dokumen, dan pengambilan dokumen — semua bisa dipantau langsung dari ponsel maupun komputer.</p>
            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn btn-accent btn-lg">Mulai Sekarang <x-icon name="arrow-right" :size="18" /></a>
                <a href="{{ route('layanan') }}" class="btn btn-outline-light btn-lg">Lihat Layanan</a>
            </div>
            <div class="hero-mini">
                <div><strong>3 Layanan</strong><span>Rapor · Legalisir · Dokumen</span></div>
                <div><strong>Real-time</strong><span>Status pengajuan terpantau</span></div>
                <div><strong>Aman</strong><span>Dikelola Tata Usaha sekolah</span></div>
            </div>
        </div>
        <div class="hero-art">
            <img src="{{ asset('img/hero.svg') }}" alt="Ilustrasi dashboard SIMDA dengan pengajuan dokumen dan notifikasi">
        </div>
    </div>
</section>

<div class="container stat-strip">
    <div class="box">
        <div class="item"><span class="ico"><x-icon name="zap" :size="24" /></span><div><strong>Cepat &amp; praktis</strong><span>Ajukan kapan saja, di mana saja</span></div></div>
        <div class="item"><span class="ico"><x-icon name="bell" :size="24" /></span><div><strong>Status transparan</strong><span>Pantau proses dari Pending hingga Selesai</span></div></div>
        <div class="item"><span class="ico"><x-icon name="shield" :size="24" /></span><div><strong>Terpusat &amp; tertib</strong><span>Arsip tersimpan rapi di satu sistem</span></div></div>
    </div>
</div>

<section class="section">
    <div class="container split">
        <div class="art-frame"><img src="{{ asset('img/about.svg') }}" alt="Ilustrasi gedung sekolah dengan perisai dokumen terverifikasi"></div>
        <div>
            <span class="tag">Tentang SIMDA</span>
            <h2 style="margin-top:12px">Satu pintu untuk semua kebutuhan dokumen akademik</h2>
            <p>Selama ini pengajuan dokumen dilakukan dengan datang langsung dan mencatat manual. SIMDA mengubah proses itu menjadi alur digital yang jelas: siswa mengajukan, Tata Usaha mengonfirmasi, dan semua riwayat tercatat dengan rapi.</p>
            <ul class="check-list">
                <li><x-icon name="check" :size="16" /> Siswa mengajukan layanan langsung dari akun pribadi</li>
                <li><x-icon name="check" :size="16" /> Admin Tata Usaha menyetujui atau menolak disertai alasan</li>
                <li><x-icon name="check" :size="16" /> Jadwal pinjam dan batas pengembalian rapor tercatat otomatis</li>
            </ul>
            <div style="margin-top:26px"><a href="{{ route('informasi') }}" class="btn btn-secondary">Pelajari tata cara <x-icon name="arrow-right" :size="16" /></a></div>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="kicker">Layanan Kami</span>
            <h2>Tiga layanan utama untuk siswa</h2>
            <p>Pilih layanan yang kamu butuhkan, ajukan lewat akun siswa, lalu tunggu konfirmasi dari Tata Usaha.</p>
        </div>
        <div class="grid-3">
            <article class="feature">
                <div class="ico"><x-icon name="book" :size="26" /></div>
                <h3>Peminjaman Rapor</h3>
                <p>Pinjam rapor untuk keperluan administrasi dengan tanggal pinjam dan batas pengembalian yang jelas.</p>
            </article>
            <article class="feature">
                <div class="ico amber"><x-icon name="badge" :size="26" /></div>
                <h3>Legalisir Dokumen</h3>
                <p>Ajukan pengesahan salinan dokumen akademik tanpa harus mengantre lama di ruang Tata Usaha.</p>
            </article>
            <article class="feature">
                <div class="ico cyan"><x-icon name="package" :size="26" /></div>
                <h3>Pengambilan Dokumen</h3>
                <p>Ajukan pengambilan dokumen akademik seperti ijazah, lalu ambil setelah pengajuanmu disetujui.</p>
            </article>
        </div>
        <div style="text-align:center;margin-top:34px"><a href="{{ route('layanan') }}" class="btn btn-primary">Lihat detail layanan <x-icon name="arrow-right" :size="16" /></a></div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-band">
            <div>
                <h2>Siap mengajukan dokumen?</h2>
                <p>Masuk dengan akun siswa, atau daftar dulu menggunakan NIS yang sudah terdaftar di sekolah.</p>
            </div>
            <div class="actions">
                <a href="{{ route('login') }}" class="btn btn-accent btn-lg">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Registrasi Siswa</a>
            </div>
        </div>
    </div>
</section>
@endsection

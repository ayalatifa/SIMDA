@extends('layouts.public')
@section('title', 'Layanan')

@section('content')
<section class="page-hero">
    <div class="container">
        <div>
            <span class="eyebrow"><span class="dot"></span> Layanan SIMDA</span>
            <h1>Tiga layanan dokumen akademik untuk siswa</h1>
            <p>Peminjaman rapor, legalisir dokumen, dan pengambilan dokumen — diajukan online, diproses oleh Tata Usaha.</p>
        </div>
        <img src="{{ asset('img/service-dokumen.svg') }}" alt="Ilustrasi map dokumen akademik">
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="service">
            <div class="service-art"><img src="{{ asset('img/service-rapor.svg') }}" alt="Ilustrasi peminjaman rapor"></div>
            <div>
                <span class="service-num">Layanan 01</span>
                <h2>Peminjaman Rapor</h2>
                <p>Pinjam rapor asli untuk keperluan administrasi, misalnya melengkapi berkas pendaftaran. Tanggal pinjam dan batas pengembalian dicatat oleh Tata Usaha sehingga mudah kamu pantau.</p>
                <div class="meta">
                    <div><b>Cocok untuk</b><span>Kebutuhan administrasi yang memerlukan rapor asli.</span></div>
                    <div><b>Yang dipantau</b><span>Tanggal pinjam &amp; batas pengembalian.</span></div>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary">Ajukan Peminjaman <x-icon name="arrow-right" :size="16" /></a>
            </div>
        </div>

        <div class="service">
            <div class="service-art"><img src="{{ asset('img/service-legalisir.svg') }}" alt="Ilustrasi legalisir dokumen dengan stempel"></div>
            <div>
                <span class="service-num">Layanan 02</span>
                <h2>Legalisir Dokumen</h2>
                <p>Ajukan pengesahan salinan dokumen akademikmu. Cukup tulis dokumen apa yang perlu dilegalisir pada kolom detail pengajuan, lalu pantau statusnya dari akunmu.</p>
                <div class="meta">
                    <div><b>Cocok untuk</b><span>Salinan dokumen yang perlu disahkan sekolah.</span></div>
                    <div><b>Yang dipantau</b><span>Status pengajuan &amp; catatan dari Tata Usaha.</span></div>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary">Ajukan Legalisir <x-icon name="arrow-right" :size="16" /></a>
            </div>
        </div>

        <div class="service">
            <div class="service-art"><img src="{{ asset('img/service-dokumen.svg') }}" alt="Ilustrasi pengambilan dokumen akademik"></div>
            <div>
                <span class="service-num">Layanan 03</span>
                <h2>Pengambilan Dokumen</h2>
                <p>Ajukan pengambilan dokumen akademik yang tersimpan di sekolah, seperti ijazah. Setelah pengajuan disetujui, kamu dapat mengambilnya di Tata Usaha.</p>
                <div class="meta">
                    <div><b>Cocok untuk</b><span>Dokumen akademik yang disimpan oleh sekolah.</span></div>
                    <div><b>Yang dipantau</b><span>Persetujuan &amp; penyelesaian pengambilan.</span></div>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary">Ajukan Pengambilan <x-icon name="arrow-right" :size="16" /></a>
            </div>
        </div>

    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="kicker">Persiapan</span>
            <h2>Sebelum mengajukan, pastikan…</h2>
        </div>
        <div class="grid-3">
            <article class="feature"><div class="ico"><x-icon name="id" :size="24" /></div><h3>Punya akun siswa</h3><p>Registrasi memakai NIS yang sudah terdaftar dan nama lengkap sesuai data sekolah.</p></article>
            <article class="feature"><div class="ico amber"><x-icon name="edit" :size="24" /></div><h3>Isi detail dengan jelas</h3><p>Tuliskan keperluan pengajuan agar Tata Usaha dapat memprosesnya lebih cepat.</p></article>
            <article class="feature"><div class="ico cyan"><x-icon name="phone" :size="24" /></div><h3>Nomor HP aktif</h3><p>Pastikan nomor HP yang kamu daftarkan dapat dihubungi bila diperlukan.</p></article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-band">
            <div><h2>Mulai ajukan layananmu</h2><p>Login atau buat akun siswa untuk mengajukan layanan dokumen.</p></div>
            <div class="actions">
                <a href="{{ route('login') }}" class="btn btn-accent btn-lg">Login / Register</a>
                <a href="{{ route('informasi') }}" class="btn btn-outline-light btn-lg">Tata Cara</a>
            </div>
        </div>
    </div>
</section>
@endsection

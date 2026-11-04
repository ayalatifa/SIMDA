@extends('layouts.public')
@section('title', 'Informasi')

@section('content')
<section class="page-hero">
    <div class="container">
        <div>
            <span class="eyebrow"><span class="dot"></span> Informasi &amp; panduan</span>
            <h1>Kegunaan dan tata cara menggunakan SIMDA</h1>
            <p>Pahami manfaat SIMDA dan ikuti langkah-langkah singkat berikut agar pengajuan dokumenmu berjalan lancar.</p>
        </div>
        <img src="{{ asset('img/info.svg') }}" alt="Ilustrasi panduan penggunaan SIMDA di ponsel">
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="kicker">Kegunaan</span>
            <h2>Apa manfaat SIMDA untuk kamu?</h2>
            <p>Dirancang agar urusan dokumen akademik menjadi lebih mudah bagi siswa dan lebih tertib bagi Tata Usaha.</p>
        </div>
        <div class="grid-4">
            <article class="feature"><div class="ico"><x-icon name="send" :size="24" /></div><h3>Ajukan online</h3><p>Kirim pengajuan layanan dokumen kapan saja tanpa datang lebih dulu ke sekolah.</p></article>
            <article class="feature"><div class="ico amber"><x-icon name="clock" :size="24" /></div><h3>Pantau status</h3><p>Lihat perkembangan pengajuan, catatan penolakan, serta jadwal pinjam rapor.</p></article>
            <article class="feature"><div class="ico cyan"><x-icon name="folder" :size="24" /></div><h3>Arsip terpusat</h3><p>Data siswa dan dokumen akademik tersimpan rapi dan mudah dicari oleh Tata Usaha.</p></article>
            <article class="feature"><div class="ico"><x-icon name="chart" :size="24" /></div><h3>Laporan rekap</h3><p>Admin mendapat rekap pengajuan per status dan per jenis layanan secara otomatis.</p></article>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="kicker">Tata Cara</span>
            <h2>Enam langkah mengajukan layanan</h2>
            <p>Ikuti urutan berikut, dari membuat akun hingga dokumenmu siap.</p>
        </div>
        <div class="steps">
            <div class="step"><h3>Registrasi akun</h3><p>Klik <b>Login / Register</b>, lalu pilih “Belum punya akun siswa? Registrasi”. Isi nama lengkap (sesuai data sekolah), username, NIS terdaftar, nomor HP, dan password.</p></div>
            <div class="step"><h3>Login</h3><p>Masuk memakai username dan password yang sudah dibuat. Admin Tata Usaha masuk menggunakan email.</p></div>
            <div class="step"><h3>Ajukan layanan</h3><p>Di dashboard siswa, pilih <b>Ajukan Layanan</b>, tentukan jenis layanan, lalu tulis detail keperluanmu.</p></div>
            <div class="step"><h3>Tunggu konfirmasi</h3><p>Tata Usaha memeriksa pengajuanmu dan memutuskan untuk menyetujui atau menolak disertai alasan.</p></div>
            <div class="step"><h3>Pantau status</h3><p>Buka <b>Riwayat Pengajuan</b> untuk melihat status, catatan, tanggal pinjam, dan batas pengembalian rapor.</p></div>
            <div class="step"><h3>Ambil dokumen</h3><p>Setelah disetujui, temui Tata Usaha sekolah untuk mengambil atau mengembalikan dokumen sesuai ketentuan.</p></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="kicker">Alur Status</span>
            <h2>Memahami status pengajuan</h2>
            <p>Setiap pengajuan melewati status berikut selama diproses.</p>
        </div>
        <div class="flow">
            <span class="badge badge-pending">Pending</span><x-icon name="arrow-right" class="icon arrow" :size="18" />
            <span class="badge badge-disetujui">Disetujui</span><x-icon name="arrow-right" class="icon arrow" :size="18" />
            <span class="badge badge-dipinjam">Dipinjam</span><x-icon name="arrow-right" class="icon arrow" :size="18" />
            <span class="badge badge-dikembalikan">Dikembalikan</span><x-icon name="arrow-right" class="icon arrow" :size="18" />
            <span class="badge badge-selesai">Selesai</span>
        </div>
        <p style="text-align:center;color:var(--muted);margin-top:18px">Jika pengajuan <span class="badge badge-ditolak">Ditolak</span>, alasannya akan tampil di kolom catatan pada riwayat pengajuanmu.</p>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="kicker">FAQ</span>
            <h2>Pertanyaan yang sering diajukan</h2>
        </div>
        <div class="faq">
            <details><summary>Registrasi saya ditolak, kenapa? <x-icon name="chevron-down" :size="20" /></summary><p>Registrasi hanya bisa dilakukan jika NIS sudah terdaftar oleh sekolah, nama lengkap sesuai dengan data siswa, dan NIS tersebut belum memiliki akun. Jika data belum sesuai, hubungi Tata Usaha.</p></details>
            <details><summary>Apakah admin bisa mendaftar lewat halaman registrasi? <x-icon name="chevron-down" :size="20" /></summary><p>Tidak. Halaman registrasi khusus untuk siswa. Akun admin dikelola oleh pihak sekolah.</p></details>
            <details><summary>Di mana saya bisa melihat alasan pengajuan ditolak? <x-icon name="chevron-down" :size="20" /></summary><p>Buka menu <b>Riwayat Pengajuan</b>. Alasan penolakan dari Tata Usaha ditampilkan pada kolom catatan.</p></details>
            <details><summary>Bagaimana jika saya lupa password? <x-icon name="chevron-down" :size="20" /></summary><p>Silakan hubungi Tata Usaha sekolah agar akunmu dapat dibantu.</p></details>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-band">
            <div><h2>Sudah paham alurnya?</h2><p>Mulai ajukan layanan dokumenmu sekarang.</p></div>
            <div class="actions">
                <a href="{{ route('login') }}" class="btn btn-accent btn-lg">Login / Register</a>
                <a href="{{ route('layanan') }}" class="btn btn-outline-light btn-lg">Lihat Layanan</a>
            </div>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $totalSiswa = \App\Models\Siswa::count();
    $totalDokumen = \App\Models\DokumenAkademik::count();
    $totalPengajuan = \App\Models\Pengajuan::count();
    $pending = \App\Models\Pengajuan::where('status', 'Pending')->count();
    $terbaru = \App\Models\Pengajuan::with('siswa')->latest()->take(5)->get();
@endphp

<section class="banner">
    <div>
        <h1>Selamat Datang di SIMDA</h1>
        <p>Sistem Manajemen Dokumen Akademik untuk membantu pengelolaan data siswa dan dokumen akademik sekolah.</p>
    </div>
    <button type="button" class="btn btn-accent" data-open="#notifModal">
        <x-icon name="clipboard" :size="18" />
        {{ $pending > 0 ? $pending . ' pengajuan menunggu' : 'Konfirmasi Pengajuan' }}
    </button>
</section>

<section class="stat-grid">
    <div class="stat"><span class="ico"><x-icon name="users" :size="26" /></span><div><div class="num">{{ $totalSiswa }}</div><div class="lbl">Total Siswa</div><div class="dsc">Data siswa yang terdaftar</div></div></div>
    <div class="stat"><span class="ico cyan"><x-icon name="folder" :size="26" /></span><div><div class="num">{{ $totalDokumen }}</div><div class="lbl">Total Dokumen</div><div class="dsc">Dokumen akademik tersimpan</div></div></div>
    <div class="stat"><span class="ico violet"><x-icon name="file" :size="26" /></span><div><div class="num">{{ $totalPengajuan }}</div><div class="lbl">Total Pengajuan</div><div class="dsc">Pengajuan layanan dokumen</div></div></div>
    <div class="stat"><span class="ico amber"><x-icon name="clock" :size="26" /></span><div><div class="num">{{ $pending }}</div><div class="lbl">Menunggu</div><div class="dsc">Perlu dikonfirmasi</div></div></div>
</section>

<h2 class="section-title">Menu Utama</h2>
<section class="quick-grid">
    <a href="{{ route('admin.siswa.index') }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
        <div class="ico"><x-icon name="users" :size="24" /></div><h3>Data Siswa</h3><p>Melihat, menambah, mengedit, dan menghapus data siswa.</p></a>
    <a href="{{ route('admin.dokumen.index') }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
        <div class="ico"><x-icon name="folder" :size="24" /></div><h3>Dokumen Akademik</h3><p>Mengelola arsip dokumen akademik siswa.</p></a>
    <a href="{{ route('admin.pengajuan.index') }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
        <div class="ico"><x-icon name="file" :size="24" /></div><h3>Pengajuan Layanan</h3><p>Mengelola pengajuan legalisir dan peminjaman rapor.</p></a>
    <a href="{{ route('admin.laporan') }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
        <div class="ico"><x-icon name="chart" :size="24" /></div><h3>Laporan &amp; Rekap</h3><p>Ringkasan pengajuan per status dan jenis layanan.</p></a>
</section>

<div class="card">
    <div class="card-head">
        <div><h2>Pengajuan Terbaru</h2><p>Lima pengajuan terakhir yang masuk ke sistem.</p></div>
        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary btn-sm">Lihat semua</a>
    </div>
    <div class="table-wrap">
        <table class="table stack">
            <thead><tr><th>Siswa</th><th>Layanan</th><th>Tanggal</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($terbaru as $item)
                <tr>
                    <td data-label="Siswa" class="td-main"><div class="cell-main">{{ $item->siswa->nama_lengkap ?? '-' }}</div><div class="cell-sub">{{ $item->siswa->kelas ?? '' }}</div></td>
                    <td data-label="Layanan">{{ $item->jenis_layanan }}</td>
                    <td data-label="Tanggal">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') : '-' }}</td>
                    <td data-label="Status"><span class="badge badge-{{ strtolower(str_replace(' ', '-', $item->status)) }}">{{ $item->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="4"><div class="empty-state"><div class="ico"><x-icon name="file" :size="28" /></div><strong>Belum ada pengajuan</strong>Pengajuan dari siswa akan tampil di sini.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="callout"><x-icon name="info" :size="20" /><div><strong>Informasi:</strong> SIMDA digunakan untuk membantu Tata Usaha dalam mengelola data siswa, dokumen akademik, serta pengajuan layanan dokumen secara lebih terorganisir.</div></div>
@endsection

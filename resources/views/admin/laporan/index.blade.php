@extends('layouts.app')
@section('title', 'Laporan & Rekap')

@section('content')
@php
    $statusRows = [
        ['Pending', $pending, 'amber'], ['Disetujui', $disetujui, ''], ['Ditolak', $ditolak, 'red'],
        ['Dipinjam', $dipinjam, 'violet'], ['Dikembalikan', $dikembalikan, 'cyan'], ['Selesai', $selesai, 'green'],
    ];
    $layananRows = [
        ['Peminjaman Rapor', $peminjamanRapor, ''], ['Legalisir', $legalisir, 'amber'],
        ['Pengambilan Ijazah', $pengambilanIjazah, 'cyan'], ['Dokumen Lainnya', $dokumenLainnya, 'violet'],
    ];
    $max = max($total, 1);
@endphp

<div class="page-head"><div><h1>Laporan &amp; Rekap</h1><p>Ringkasan data pengajuan layanan dokumen akademik siswa.</p></div></div>

<section class="stat-grid">
    <div class="stat"><span class="ico violet"><x-icon name="file" :size="26" /></span><div><div class="num">{{ $total }}</div><div class="lbl">Total Pengajuan</div></div></div>
    <div class="stat"><span class="ico"><x-icon name="book" :size="26" /></span><div><div class="num">{{ $peminjamanRapor }}</div><div class="lbl">Peminjaman Rapor</div></div></div>
    <div class="stat"><span class="ico amber"><x-icon name="badge" :size="26" /></span><div><div class="num">{{ $legalisir }}</div><div class="lbl">Legalisir</div></div></div>
    <div class="stat"><span class="ico cyan"><x-icon name="package" :size="26" /></span><div><div class="num">{{ $pengambilanIjazah }}</div><div class="lbl">Pengambilan Ijazah</div></div></div>
    <div class="stat"><span class="ico green"><x-icon name="folder" :size="26" /></span><div><div class="num">{{ $dokumenLainnya }}</div><div class="lbl">Dokumen Lainnya</div></div></div>
</section>

<div class="two-col">
    <div class="card">
        <div class="card-head"><div><h2>Rekap Berdasarkan Status</h2><p>Jumlah pengajuan berdasarkan status proses layanan.</p></div></div>
        <div class="card-body">
            @foreach ($statusRows as [$nama, $jumlah, $warna])
                <div class="bar-row">
                    <span class="name">{{ $nama }}</span>
                    <div class="bar-track"><div class="bar-fill {{ $warna }}" data-w="{{ round($jumlah / $max * 100) }}"></div></div>
                    <span class="val">{{ $jumlah }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-head"><div><h2>Rekap Jenis Layanan</h2><p>Jumlah pengajuan berdasarkan jenis layanan.</p></div></div>
        <div class="card-body">
            @foreach ($layananRows as [$nama, $jumlah, $warna])
                <div class="bar-row">
                    <span class="name">{{ $nama }}</span>
                    <div class="bar-track"><div class="bar-fill {{ $warna }}" data-w="{{ round($jumlah / $max * 100) }}"></div></div>
                    <span class="val">{{ $jumlah }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card" style="margin-top:22px">
    <div class="card-head"><div><h2>Tabel Rekap</h2><p>Rincian angka untuk status dan jenis layanan.</p></div></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>No</th><th>Status</th><th>Jumlah</th></tr></thead>
            <tbody>
            @foreach ($statusRows as $i => [$nama, $jumlah])
                <tr><td class="num">{{ $i + 1 }}</td><td><span class="badge badge-{{ strtolower($nama) }}">{{ $nama }}</span></td><td><b>{{ $jumlah }}</b></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-wrap" style="border-top:1px solid var(--line)">
        <table class="table">
            <thead><tr><th>No</th><th>Jenis Layanan</th><th>Jumlah</th></tr></thead>
            <tbody>
            @foreach ($layananRows as $i => [$nama, $jumlah])
                <tr><td class="num">{{ $i + 1 }}</td><td>{{ $nama }}</td><td><b>{{ $jumlah }}</b></td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

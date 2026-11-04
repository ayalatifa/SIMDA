@extends('layouts.app')
@section('title', 'Dokumen Saya')

@section('content')
@php
    $jenisList = $dokumen->pluck('jenis_dokumen')->unique()->values();
@endphp

<div class="page-head">
    <div><h1>Dokumen Saya</h1><p>Dokumen akademik pribadi yang sudah didaftarkan oleh Tata Usaha sekolah.</p></div>
</div>

<div class="card">
    <div class="card-head">
        <div><h2>Arsip Dokumen</h2><p>{{ $dokumen->count() }} dokumen tercatat atas nama {{ $siswa->nama_lengkap }}.</p></div>
    </div>

    @if ($dokumen->count() > 0)
        <div class="chips" aria-label="Filter jenis dokumen">
            <button type="button" class="chip active" data-chip="">Semua <span class="n">{{ $dokumen->count() }}</span></button>
            @foreach ($jenisList as $jenis)
                <button type="button" class="chip" data-chip="{{ $jenis }}">{{ $jenis }} <span class="n">{{ $dokumen->where('jenis_dokumen', $jenis)->count() }}</span></button>
            @endforeach
        </div>
        <div class="toolbar">
            <div class="search-box"><x-icon name="search" :size="18" />
                <input type="search" placeholder="Cari dokumen..." data-live-search="#docGrid" aria-label="Cari dokumen"></div>
            <span class="result-info" data-result-info></span>
        </div>
    @endif

    <div class="doc-grid" id="docGrid">
        @forelse ($dokumen as $item)
            @php
                $ext = strtolower(pathinfo($item->file_dokumen ?? '', PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                $url = $item->file_dokumen ? asset('storage/' . $item->file_dokumen) : null;
            @endphp
            <article class="doc" data-search-item data-status="{{ $item->jenis_dokumen }}">
                <div class="doc-thumb">
                    @if ($url && $isImage)
                        <img src="{{ $url }}" alt="Pratinjau {{ $item->jenis_dokumen }}" loading="lazy">
                    @else
                        <span class="big"><x-icon name="{{ $item->jenis_dokumen === 'Rapor' ? 'book' : ($item->jenis_dokumen === 'Sertifikat' ? 'badge' : 'file') }}" :size="30" /></span>
                    @endif
                    @if ($ext)<span class="ext">{{ strtoupper($ext) }}</span>@endif
                </div>
                <div class="doc-body">
                    <span class="tag">{{ $item->jenis_dokumen }}</span>
                    <h3>{{ $item->jenis_dokumen }}</h3>
                    <p>{{ $item->keterangan ?: 'Tidak ada keterangan.' }}</p>
                    <p style="margin-top:6px;font-size:12.5px"><x-icon name="calendar" :size="13" /> Diarsipkan {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="doc-foot">
                    @if ($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm"><x-icon name="eye" :size="15" /> Lihat</a>
                        <a href="{{ $url }}" download class="btn btn-secondary btn-sm"><x-icon name="download" :size="15" /> Unduh</a>
                    @else
                        <span class="hint" style="margin:0">File belum diunggah oleh Tata Usaha.</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="empty-state" style="grid-column:1/-1">
                <div class="ico"><x-icon name="folder" :size="28" /></div>
                <strong>Belum ada dokumen</strong>
                Dokumen akademikmu (rapor, sertifikat, dll.) akan muncul di sini setelah didaftarkan oleh Tata Usaha.
            </div>
        @endforelse
    </div>
    <div class="empty-state" data-search-empty hidden><div class="ico"><x-icon name="search" :size="28" /></div><strong>Tidak ada hasil</strong>Coba kata kunci lain.</div>
</div>

<div class="callout"><x-icon name="info" :size="20" /><div>Dokumen ditambahkan dan dikelola oleh Tata Usaha. Jika ada dokumenmu yang belum tampil, silakan hubungi Tata Usaha atau ajukan layanan melalui menu Riwayat Pengajuan.</div></div>
@endsection

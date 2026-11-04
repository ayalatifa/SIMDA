@extends('layouts.app')
@section('title', 'Pengajuan Saya')

@section('content')
<div class="page-head">
    <div><h1>Pengajuan Saya</h1><p>Pantau status layanan akademik yang kamu ajukan.</p></div>
    <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary"><x-icon name="plus" :size="18" /> Ajukan Layanan</a>
</div>

<div class="card">
    <div class="card-head"><div><h2>Riwayat Layanan Dokumen</h2><p>Periksa status dan catatan dari Tata Usaha pada tabel berikut.</p></div></div>
    <div class="table-wrap">
        <table class="table stack">
            <thead><tr><th>No.</th><th>Tanggal</th><th>Jenis Layanan</th><th>Detail</th><th>Status</th><th>Catatan</th></tr></thead>
            <tbody>
            @forelse ($pengajuan as $item)
                <tr>
                    <td class="num">{{ $loop->iteration }}</td>
                    <td data-label="Tanggal">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') : '-' }}</td>
                    <td class="td-main"><span class="cell-main">{{ $item->jenis_layanan }}</span></td>
                    <td data-label="Detail">{{ $item->detail ?: '-' }}</td>
                    <td data-label="Status"><span class="badge badge-{{ strtolower($item->status) }}">{{ $item->status }}</span></td>
                    <td data-label="Catatan">
                        @if ($item->status === 'Ditolak')
                            <div class="note-reject"><b>Alasan penolakan</b>{{ $item->catatan ?: 'Admin belum memberikan alasan penolakan.' }}</div>
                        @else - @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><div class="empty-state"><div class="ico"><x-icon name="file" :size="28" /></div><strong>Kamu belum memiliki pengajuan layanan</strong>Klik tombol “Ajukan Layanan” untuk memulai.</div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="callout"><x-icon name="info" :size="20" /><div><strong>Informasi:</strong> Pengajuan yang kamu kirim akan menunggu keputusan Tata Usaha. Jika ditolak, alasan penolakan akan ditampilkan pada kolom Catatan.</div></div>
@endsection

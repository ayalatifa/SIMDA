@extends('layouts.app')
@section('title', 'Pengajuan Layanan')

@section('content')
@php
    $filters = [
        '' => 'Semua Pengajuan',
        'Peminjaman Rapor' => 'Peminjaman Rapor',
        'Legalisir' => 'Legalisir',
        'Pengambilan Ijazah' => 'Pengambilan Ijazah',
        'Lainnya' => 'Dokumen Lainnya',
    ];
    $kat = request('jenis_layanan') ? \App\Models\Pengajuan::kategoriDari(request('jenis_layanan')) : null;
    $extra = match ($kat) { 'rapor' => 3, 'legalisir', 'ijazah', 'lainnya' => 1, default => 0 };
    $cols = 7 + $extra;
@endphp

<div class="page-head">
    <div><h1>Pengajuan Layanan</h1><p>Kelola pengajuan layanan dokumen akademik siswa.</p></div>
    <button type="button" class="btn btn-primary" data-open="#modalPengajuan"><x-icon name="plus" :size="18" /> Tambah Pengajuan</button>
</div>

<div class="card">
    <nav class="chips" aria-label="Filter jenis layanan">
        @foreach ($filters as $val => $label)
            @php $active = $val === '' ? !request('jenis_layanan') : $kat === \App\Models\Pengajuan::kategoriDari($val); @endphp
            <a href="{{ $val === '' ? route('admin.pengajuan.index') : route('admin.pengajuan.index', ['jenis_layanan' => $val]) }}" class="chip {{ $active ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </nav>

    <div class="toolbar">
        <div class="search-box"><x-icon name="search" :size="18" />
            <input type="search" placeholder="Cari nama siswa, NISN, atau detail..." data-live-search="#pengajuanBody" aria-label="Cari pengajuan"></div>
        <span class="result-info" data-result-info></span>
    </div>

    <div class="table-wrap">
        <table class="table stack">
            <thead>
                <tr>
                    <th>No</th><th>Nama Siswa</th><th>Jenis Layanan</th><th>Detail</th><th>Tanggal Pengajuan</th><th>Status</th>
                    @if ($kat === 'rapor')<th>Tanggal Pinjam</th><th>Batas Pengembalian</th><th>Tanggal Pengembalian</th>@endif
                    @if ($kat === 'legalisir')<th>Tanggal Legalisir</th>@endif
                    @if ($kat === 'ijazah' || $kat === 'lainnya')<th>Tanggal Pengambilan</th>@endif
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pengajuanBody">
            @forelse ($pengajuan as $item)
                @php $statusClass = strtolower(str_replace(' ', '-', $item->status)); @endphp
                <tr data-search-item>
                    <td class="num">{{ $loop->iteration }}</td>
                    <td class="td-main">
                        <div class="cell-person"><span class="avatar">{{ strtoupper(mb_substr($item->siswa->nama_lengkap, 0, 1)) }}</span>
                            <div><div class="cell-main">{{ $item->siswa->nama_lengkap }}</div><div class="cell-sub">NISN: {{ $item->siswa->nisn }}</div></div></div>
                    </td>
                    <td data-label="Layanan"><span class="tag">{{ $item->jenis_layanan }}</span></td>
                    <td data-label="Detail">{{ $item->detail ?? '-' }}</td>
                    <td data-label="Tanggal">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') }}</td>
                    <td data-label="Status"><span class="badge badge-{{ $statusClass }}">{{ $item->status }}</span></td>
                    @if ($kat === 'rapor')
                        <td data-label="Tgl Pinjam">{{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                        <td data-label="Batas Kembali">{{ $item->batas_pengembalian ? $item->batas_pengembalian->format('d/m/Y') : '-' }}</td>
                        <td data-label="Tgl Kembali">{{ $item->tanggal_pengembalian ? $item->tanggal_pengembalian->format('d/m/Y') : '-' }}</td>
                    @elseif ($kat === 'legalisir')
                        <td data-label="Tgl Legalisir">{{ $item->tanggal_legalisir ? $item->tanggal_legalisir->format('d/m/Y') : '-' }}</td>
                    @elseif ($kat === 'ijazah' || $kat === 'lainnya')
                        <td data-label="Tgl Pengambilan">{{ $item->tanggal_pengambilan ? $item->tanggal_pengambilan->format('d/m/Y') : '-' }}</td>
                    @endif
                    <td class="td-actions">
                        <div class="kebab">
                            <button type="button" class="kebab-btn" aria-haspopup="menu" aria-expanded="false" aria-label="Aksi"><x-icon name="more" :size="18" /></button>
                            <div class="kebab-menu" role="menu">
                                <a href="{{ route('admin.pengajuan.edit', $item) }}" class="kebab-item" role="menuitem"><x-icon name="edit" :size="16" /> Edit</a>
                                <form action="{{ route('admin.pengajuan.destroy', $item) }}" method="POST" data-confirm="Yakin ingin menghapus pengajuan {{ $item->jenis_layanan }} milik {{ $item->siswa->nama_lengkap }}?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="kebab-item danger" role="menuitem"><x-icon name="trash" :size="16" /> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ $cols }}"><div class="empty-state"><div class="ico"><x-icon name="file" :size="28" /></div><strong>Belum ada data pengajuan</strong>Pengajuan yang masuk akan tampil di sini.</div></td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="empty-state" data-search-empty hidden><div class="ico"><x-icon name="search" :size="28" /></div><strong>Tidak ada hasil</strong>Coba kata kunci lain.</div>
    </div>
</div>
@endsection

@push('modals')
@php $daftarSiswa = \App\Models\Siswa::orderBy('nama_lengkap')->get(); $mo = old('_modal') === 'pengajuan'; @endphp
<div class="modal dlg-wrap" id="modalPengajuan" role="dialog" aria-modal="true" aria-labelledby="ttlPengajuan" @if($errors->any() && $mo) data-autoopen @endif>
    <div class="dlg lg">
        <form action="{{ route('admin.pengajuan.store') }}" method="POST" data-once data-service-form>
            @csrf
            <input type="hidden" name="_modal" value="pengajuan">
            <div class="dlg-head">
                <span class="ico"><x-icon name="file" :size="22" /></span>
                <div><h3 id="ttlPengajuan">Tambah Pengajuan</h3><p>Buat pengajuan layanan dokumen untuk siswa.</p></div>
                <button type="button" class="dlg-x" data-close aria-label="Tutup"><x-icon name="x" :size="18" /></button>
            </div>
            <div class="dlg-body">
                @if ($errors->any() && $mo)
                    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
                @endif
                <div class="field"><label for="p_siswa">Siswa <span class="req">*</span></label>
                    <select name="siswa_id" id="p_siswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $sw)
                            <option value="{{ $sw->id }}" {{ $mo && old('siswa_id') == $sw->id ? 'selected' : '' }}>{{ $sw->nama_lengkap }} - NISN {{ $sw->nisn }}</option>
                        @endforeach
                    </select></div>
                <div class="field"><label for="p_jenis">Jenis Layanan <span class="req">*</span></label>
                    <select name="jenis_layanan" id="p_jenis" data-service-select required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach (\App\Models\Pengajuan::LAYANAN as $opt)
                            <option value="{{ $opt }}" {{ $mo && old('jenis_layanan') == $opt ? 'selected' : '' }}>{{ $opt === 'Lainnya' ? 'Dokumen Lainnya' : $opt }}</option>
                        @endforeach
                    </select>
                    <span class="hint">Tanggal pengajuan tercatat otomatis saat disimpan.</span></div>
                <div class="field"><label for="p_detail">Detail Pengajuan</label>
                    <textarea name="detail" id="p_detail" placeholder="Contoh: Meminjam rapor untuk keperluan administrasi...">{{ $mo ? old('detail') : '' }}</textarea></div>
                @include('partials.pengajuan-blocks', ['uid' => 'pm', 'useOld' => $mo])
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endpush

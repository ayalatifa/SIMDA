@extends('layouts.app')
@section('title', 'Dokumen Akademik')

@section('content')
<div class="page-head">
    <div><h1>Dokumen Akademik</h1><p>Kelola arsip dokumen akademik siswa.</p></div>
    <button type="button" class="btn btn-primary" data-open="#modalDokumen"><x-icon name="plus" :size="18" /> Tambah Dokumen</button>
</div>

<div class="card">
    <div class="card-head"><div><h2>Daftar Dokumen</h2><p>Data dokumen akademik yang tersimpan dalam sistem.</p></div></div>
    <div class="toolbar">
        <div class="search-box"><x-icon name="search" :size="18" />
            <input type="search" placeholder="Cari nama siswa, NISN, atau jenis dokumen..." data-live-search="#dokumenBody" aria-label="Cari dokumen"></div>
        <span class="result-info" data-result-info></span>
    </div>
    <div class="table-wrap">
        <table class="table stack">
            <thead><tr><th>No</th><th>Nama Siswa</th><th>NISN</th><th>Jenis Dokumen</th><th>Keterangan</th><th>File</th><th>Aksi</th></tr></thead>
            <tbody id="dokumenBody">
            @forelse ($dokumen as $item)
                <tr data-search-item>
                    <td class="num">{{ $loop->iteration }}</td>
                    <td class="td-main"><div class="cell-person"><span class="avatar">{{ strtoupper(mb_substr($item->siswa->nama_lengkap, 0, 1)) }}</span><div class="cell-main">{{ $item->siswa->nama_lengkap }}</div></div></td>
                    <td data-label="NISN">{{ $item->siswa->nisn }}</td>
                    <td data-label="Jenis"><span class="tag">{{ $item->jenis_dokumen }}</span></td>
                    <td data-label="Keterangan">{{ $item->keterangan ?? '-' }}</td>
                    <td data-label="File">
                        @if ($item->file_dokumen)
                            <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" rel="noopener" class="link-file"><x-icon name="external" :size="14" /> Lihat File</a>
                        @else - @endif
                    </td>
                    <td class="td-actions">
                        <div class="kebab">
                            <button type="button" class="kebab-btn" aria-haspopup="menu" aria-expanded="false" aria-label="Aksi"><x-icon name="more" :size="18" /></button>
                            <div class="kebab-menu" role="menu">
                                <a href="{{ route('admin.dokumen.edit', $item) }}" class="kebab-item" role="menuitem"><x-icon name="edit" :size="16" /> Edit</a>
                                <form action="{{ route('admin.dokumen.destroy', $item) }}" method="POST" data-confirm="Yakin ingin menghapus dokumen {{ $item->jenis_dokumen }} milik {{ $item->siswa->nama_lengkap }}?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="kebab-item danger" role="menuitem"><x-icon name="trash" :size="16" /> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7"><div class="empty-state"><div class="ico"><x-icon name="folder" :size="28" /></div><strong>Belum ada dokumen akademik</strong>Klik “Tambah Dokumen” untuk mengarsipkan dokumen pertama.</div></td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="empty-state" data-search-empty hidden><div class="ico"><x-icon name="search" :size="28" /></div><strong>Tidak ada hasil</strong>Coba kata kunci lain.</div>
    </div>
</div>
@endsection

@push('modals')
@php $daftarSiswa = \App\Models\Siswa::orderBy('nama_lengkap')->get(); $mo = old('_modal') === 'dokumen'; @endphp
<div class="modal dlg-wrap" id="modalDokumen" role="dialog" aria-modal="true" aria-labelledby="ttlDokumen" @if($errors->any() && $mo) data-autoopen @endif>
    <div class="dlg">
        <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data" data-once>
            @csrf
            <input type="hidden" name="_modal" value="dokumen">
            <div class="dlg-head">
                <span class="ico"><x-icon name="folder" :size="22" /></span>
                <div><h3 id="ttlDokumen">Tambah Dokumen Akademik</h3><p>Arsipkan dokumen akademik milik siswa.</p></div>
                <button type="button" class="dlg-x" data-close aria-label="Tutup"><x-icon name="x" :size="18" /></button>
            </div>
            <div class="dlg-body">
                @if ($errors->any() && $mo)
                    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
                @endif
                <div class="field"><label for="d_siswa">Siswa <span class="req">*</span></label>
                    <select name="siswa_id" id="d_siswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($daftarSiswa as $sw)
                            <option value="{{ $sw->id }}" {{ $mo && old('siswa_id') == $sw->id ? 'selected' : '' }}>{{ $sw->nama_lengkap }} - NISN {{ $sw->nisn }}</option>
                        @endforeach
                    </select></div>
                <div class="field"><label for="d_jenis">Jenis Dokumen <span class="req">*</span></label>
                    <select name="jenis_dokumen" id="d_jenis" required>
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        @foreach (['Rapor' => 'Rapor', 'Ijazah' => 'Ijazah', 'Sertifikat' => 'Sertifikat', 'Lainnya' => 'Dokumen Lainnya'] as $val => $lbl)
                            <option value="{{ $val }}" {{ $mo && old('jenis_dokumen') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select></div>
                <div class="field"><label for="d_ket">Keterangan</label>
                    <textarea name="keterangan" id="d_ket" placeholder="Tambahkan keterangan jika diperlukan...">{{ $mo ? old('keterangan') : '' }}</textarea></div>
                <div class="field"><label for="d_file">File Dokumen</label>
                    <input type="file" name="file_dokumen" id="d_file" accept=".pdf,.jpg,.jpeg,.png">
                    <span class="hint">Format: PDF, JPG, JPEG, atau PNG.</span></div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endpush

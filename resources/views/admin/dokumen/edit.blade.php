@extends('layouts.app')
@section('title', 'Edit Dokumen')

@section('content')
<a href="{{ route('admin.dokumen.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Dokumen Akademik</a>
<div class="page-head"><div><h1>Edit Dokumen Akademik</h1><p>Perbarui informasi dokumen akademik siswa.</p></div></div>
@include('partials.errors')

<div class="card"><div class="card-body narrow">
    <form action="{{ route('admin.dokumen.update', $dokumen) }}" method="POST" enctype="multipart/form-data" data-once>
        @csrf @method('PUT')
        <div class="form-grid">
            <div class="field full">
                <label for="siswa_id">Siswa <span class="req">*</span></label>
                <select name="siswa_id" id="siswa_id" required>
                    @foreach ($siswa as $item)
                        <option value="{{ $item->id }}" {{ old('siswa_id', $dokumen->siswa_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_lengkap }} - NISN {{ $item->nisn }}</option>
                    @endforeach
                </select>
                @error('siswa_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="jenis_dokumen">Jenis Dokumen <span class="req">*</span></label>
                <select name="jenis_dokumen" id="jenis_dokumen" required>
                    @foreach (['Rapor' => 'Rapor', 'Ijazah' => 'Ijazah', 'Sertifikat' => 'Sertifikat', 'Lainnya' => 'Dokumen Lainnya'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('jenis_dokumen', $dokumen->jenis_dokumen) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('jenis_dokumen')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Tambahkan keterangan dokumen...">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                @error('keterangan')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="file_dokumen">File Dokumen</label>
                @if ($dokumen->file_dokumen)
                    <div class="callout" style="margin-bottom:12px"><x-icon name="file" :size="20" /><div>File saat ini: <a href="{{ asset('storage/' . $dokumen->file_dokumen) }}" target="_blank" rel="noopener"><strong>Lihat File</strong></a></div></div>
                @else
                    <div class="callout" style="margin-bottom:12px"><x-icon name="info" :size="20" /><div>Belum ada file yang tersimpan.</div></div>
                @endif
                <input type="file" name="file_dokumen" id="file_dokumen" accept=".pdf,.jpg,.jpeg,.png">
                <span class="hint">Unggah file baru hanya jika ingin mengganti file saat ini.</span>
                @error('file_dokumen')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Perubahan</button>
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection

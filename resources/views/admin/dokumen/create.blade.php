@extends('layouts.app')
@section('title', 'Tambah Dokumen')

@section('content')
<a href="{{ route('admin.dokumen.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Dokumen Akademik</a>
<div class="page-head"><div><h1>Tambah Dokumen Akademik</h1><p>Tambahkan dokumen akademik siswa ke dalam sistem.</p></div></div>
@include('partials.errors')

<div class="card"><div class="card-body narrow">
    <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data" data-once>
        @csrf
        <div class="form-grid">
            <div class="field full">
                <label for="siswa_id">Siswa <span class="req">*</span></label>
                <select name="siswa_id" id="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($siswa as $item)
                        <option value="{{ $item->id }}" {{ old('siswa_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_lengkap }} - NISN {{ $item->nisn }}</option>
                    @endforeach
                </select>
                @error('siswa_id')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="jenis_dokumen">Jenis Dokumen <span class="req">*</span></label>
                <select name="jenis_dokumen" id="jenis_dokumen" required>
                    <option value="">-- Pilih Jenis Dokumen --</option>
                    @foreach (['Rapor' => 'Rapor', 'Ijazah' => 'Ijazah', 'Sertifikat' => 'Sertifikat', 'Lainnya' => 'Dokumen Lainnya'] as $val => $lbl)
                        <option value="{{ $val }}" {{ old('jenis_dokumen') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                @error('jenis_dokumen')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="file">File Dokumen</label>
                <input type="file" name="file_dokumen" id="file" accept=".pdf,.jpg,.jpeg,.png">
                <span class="hint">Format yang dapat diunggah: PDF, JPG, JPEG, atau PNG.</span>
                @error('file_dokumen')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Dokumen</button>
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div></div>
@endsection

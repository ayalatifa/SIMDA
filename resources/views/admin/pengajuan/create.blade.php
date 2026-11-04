@extends('layouts.app')
@section('title', 'Tambah Pengajuan')

@section('content')
<a href="{{ route('admin.pengajuan.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Pengajuan</a>
<div class="page-head"><div><h1>Tambah Pengajuan</h1><p>Buat pengajuan layanan dokumen akademik siswa.</p></div></div>
@include('partials.errors')

<div class="card">
    <div class="card-head"><div><h2>Informasi Pengajuan</h2><p>Lengkapi data berikut untuk membuat pengajuan layanan.</p></div></div>
    <div class="card-body narrow">
        <form action="{{ route('admin.pengajuan.store') }}" method="POST" data-once data-service-form>
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
                <div class="field">
                    <label for="jenis_layanan">Jenis Layanan <span class="req">*</span></label>
                    <select name="jenis_layanan" id="jenis_layanan" data-service-select required>
                        <option value="">-- Pilih Jenis Layanan --</option>
                        @foreach (\App\Models\Pengajuan::LAYANAN as $opt)
                            <option value="{{ $opt }}" {{ old('jenis_layanan') == $opt ? 'selected' : '' }}>{{ $opt === 'Lainnya' ? 'Dokumen Lainnya' : $opt }}</option>
                        @endforeach
                    </select>
                    @error('jenis_layanan')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field full">
                    <label for="detail">Detail Pengajuan</label>
                    <textarea name="detail" id="detail" placeholder="Contoh: Meminjam rapor untuk keperluan administrasi...">{{ old('detail') }}</textarea>
                    <span class="hint">Jelaskan kebutuhan atau informasi terkait pengajuan.</span>
                    @error('detail')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-section">Informasi Layanan</div>
            @include('partials.pengajuan-blocks', ['uid' => 'cr', 'useOld' => $errors->any()])
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Pengajuan</button>
                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

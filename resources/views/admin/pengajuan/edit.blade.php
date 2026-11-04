@extends('layouts.app')
@section('title', 'Edit Pengajuan')

@section('content')
<a href="{{ route('admin.pengajuan.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Pengajuan</a>
<div class="page-head"><div><h1>Edit Pengajuan</h1><p>Perbarui data dan status pengajuan layanan dokumen akademik.</p></div></div>
@include('partials.errors')

<div class="card">
    <div class="card-head"><div><h2>Informasi Pengajuan</h2><p>Perbarui informasi pengajuan sesuai kondisi layanan.</p></div></div>
    <div class="card-body narrow">
        <form action="{{ route('admin.pengajuan.update', $pengajuan) }}" method="POST" data-once data-service-form>
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="field full">
                    <label for="siswa_id">Siswa <span class="req">*</span></label>
                    <select name="siswa_id" id="siswa_id" required>
                        @foreach ($siswa as $item)
                            <option value="{{ $item->id }}" {{ old('siswa_id', $pengajuan->siswa_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_lengkap }} - NISN {{ $item->nisn }}</option>
                        @endforeach
                    </select>
                    @error('siswa_id')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="jenis_layanan">Jenis Layanan <span class="req">*</span></label>
                    <select name="jenis_layanan" id="jenis_layanan" data-service-select required>
                        <option value="">-- Pilih Jenis Layanan --</option>
                        @php
                            $opsiLayanan = \App\Models\Pengajuan::LAYANAN;
                            if ($pengajuan->jenis_layanan && !in_array($pengajuan->jenis_layanan, $opsiLayanan)) {
                                $opsiLayanan[] = $pengajuan->jenis_layanan;
                            }
                        @endphp
                        @foreach ($opsiLayanan as $opt)
                            <option value="{{ $opt }}" {{ old('jenis_layanan', $pengajuan->jenis_layanan) == $opt ? 'selected' : '' }}>{{ $opt === 'Lainnya' ? 'Dokumen Lainnya' : $opt }}</option>
                        @endforeach
                    </select>
                    @error('jenis_layanan')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="tanggal_pengajuan">Tanggal Pengajuan <span class="auto-chip"><x-icon name="zap" :size="12" /> Otomatis</span></label>
                    <input type="text" id="tanggal_pengajuan" value="{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}" disabled>
                    <span class="hint">Tercatat otomatis saat pengajuan dibuat dan tidak dapat diubah.</span>
                </div>
                <div class="field full">
                    <label for="detail">Detail Pengajuan</label>
                    <textarea name="detail" id="detail">{{ old('detail', $pengajuan->detail) }}</textarea>
                    @error('detail')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-section">Status dan Proses Layanan</div>
            <div class="field">
                <label for="status">Status <span class="req">*</span></label>
                <select name="status" id="status" required>
                    @foreach (['Pending', 'Disetujui', 'Ditolak', 'Dipinjam', 'Dikembalikan', 'Selesai'] as $status)
                        <option value="{{ $status }}" {{ old('status', $pengajuan->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <span class="hint">Pilih status sesuai proses pengajuan saat ini.</span>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-section">Informasi Layanan</div>
            @include('partials.pengajuan-blocks', ['uid' => 'ed', 'edit' => true, 'p' => $pengajuan, 'useOld' => $errors->any()])

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Perubahan</button>
                <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

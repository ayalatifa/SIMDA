@extends('layouts.app')
@section('title', 'Tambah Siswa')

@section('content')
<a href="{{ route('admin.siswa.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Data Siswa</a>
<div class="page-head"><div><h1>Tambah Data Siswa</h1><p>Tambahkan data siswa baru ke dalam sistem.</p></div></div>
@include('partials.errors')

<div class="card">
    <div class="card-head"><div><h2>Form Data Siswa</h2><p>Lengkapi informasi siswa berikut.</p></div></div>
    <div class="card-body narrow">
        <form action="{{ route('admin.siswa.store') }}" method="POST" data-once>
            @csrf
            <div class="form-grid">
                <div class="field full"><label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap siswa" required></div>
                <div class="field"><label for="nisn">NISN <span class="req">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN" required></div>
                <div class="field"><label for="nis">NIS <span class="req">*</span></label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis') }}" placeholder="Masukkan NIS" required></div>
                <div class="field full"><label for="kelas">Kelas <span class="req">*</span></label>
                    <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1" required>
                    <span class="hint">NIS digunakan siswa saat registrasi akun, pastikan data benar.</span></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Data Siswa</button>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

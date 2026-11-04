@extends('layouts.app')
@section('title', 'Edit Siswa')

@section('content')
<a href="{{ route('admin.siswa.index') }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Data Siswa</a>
<div class="page-head"><div><h1>Edit Data Siswa</h1><p>Perbarui informasi data siswa yang tersimpan dalam sistem.</p></div></div>
@include('partials.errors')

<div class="card">
    <div class="card-head"><div><h2>Form Edit Data Siswa</h2><p>Ubah informasi siswa kemudian simpan perubahan.</p></div></div>
    <div class="card-body narrow">
        <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST" data-once>
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="field"><label for="nisn">NISN <span class="req">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" placeholder="Masukkan NISN" required></div>
                <div class="field"><label for="nis">NIS <span class="req">*</span></label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" placeholder="Masukkan NIS" required></div>
                <div class="field full"><label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" placeholder="Masukkan nama lengkap siswa" required></div>
                <div class="field"><label for="kelas">Kelas <span class="req">*</span></label>
                    <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" placeholder="Contoh: XII RPL 1" required></div>
                <div class="field"><label for="no_hp">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}" placeholder="Masukkan nomor HP"></div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan Perubahan</button>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

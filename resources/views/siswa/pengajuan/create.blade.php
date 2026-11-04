@extends('layouts.app')
@section('title', 'Ajukan Layanan')

@section('content')
<a href="{{ route('siswa.dashboard', ['halaman' => 'riwayat']) }}" class="back-link"><x-icon name="arrow-left" :size="16" /> Kembali ke Riwayat Pengajuan</a>
<div class="page-head"><div><h1>Ajukan Layanan</h1><p>Isi formulir berikut untuk mengajukan layanan akademik.</p></div></div>
@include('partials.errors')

<div class="card">
    <div class="card-head"><div><h2>Formulir Pengajuan Layanan</h2><p>Lengkapi formulir, lalu kirim untuk diproses Tata Usaha.</p></div></div>
    <div class="card-body narrow">
        <form action="{{ route('siswa.pengajuan.store') }}" method="POST" data-once data-service-form>
            @csrf
            <div class="field">
                <label for="jenis_layanan">Jenis Layanan <span class="req">*</span></label>
                <select name="jenis_layanan" id="jenis_layanan" data-service-select required>
                    <option value="">-- Pilih layanan --</option>
                    @foreach (\App\Models\Pengajuan::LAYANAN as $opt)
                        <option value="{{ $opt }}" {{ old('jenis_layanan') == $opt ? 'selected' : '' }}>{{ $opt === 'Lainnya' ? 'Dokumen Lainnya' : $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="detail">Detail Pengajuan</label>
                <textarea name="detail" id="detail" rows="4" maxlength="1000" placeholder="Tuliskan keperluan atau keterangan tambahan...">{{ old('detail') }}</textarea>
                <span class="hint">Contoh: dokumen yang akan dilegalisir atau alasan meminjam rapor.</span>
            </div>
            @include('partials.pengajuan-blocks', ['uid' => 'sc', 'useOld' => $errors->any(), 'minToday' => true])
            <div class="callout" style="margin-top:16px"><x-icon name="info" :size="20" /><div>Setelah dikirim, pengajuanmu berstatus <b>Pending</b> hingga dikonfirmasi Tata Usaha. Pantau di menu Riwayat Pengajuan.</div></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><x-icon name="send" :size="17" /> Kirim Pengajuan</button>
                <a href="{{ route('siswa.dashboard', ['halaman' => 'riwayat']) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

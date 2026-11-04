@extends('layouts.app')

@php
    $halaman = request('halaman', 'dashboard');
    $halaman = in_array($halaman, ['dashboard', 'riwayat']) ? $halaman : 'dashboard';
    $jmlTotal = $pengajuan->count();
    $jmlPending = $pengajuan->where('status', 'Pending')->count();
    $jmlSelesai = $pengajuan->where('status', 'Selesai')->count();
@endphp

@section('title', $halaman === 'riwayat' ? 'Riwayat Pengajuan' : 'Dashboard')

@section('content')
@if ($halaman === 'dashboard')
    <section class="banner">
        <div>
            <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p>Pantau ringkasan pengajuan layanan dokumen akademikmu melalui halaman dashboard ini.</p>
        </div>
        <button type="button" class="btn btn-accent" data-open="#modalAjukan"><x-icon name="plus" :size="18" /> Ajukan Layanan</button>
    </section>

    <section class="stat-grid">
        <div class="stat"><span class="ico violet"><x-icon name="file" :size="26" /></span><div><div class="num">{{ $jmlTotal }}</div><div class="lbl">Total Pengajuan</div><div class="dsc">Seluruh pengajuan layananmu</div></div></div>
        <div class="stat"><span class="ico amber"><x-icon name="clock" :size="26" /></span><div><div class="num">{{ $jmlPending }}</div><div class="lbl">Menunggu Persetujuan</div><div class="dsc">Menunggu keputusan Tata Usaha</div></div></div>
        <div class="stat"><span class="ico green"><x-icon name="check-circle" :size="26" /></span><div><div class="num">{{ $jmlSelesai }}</div><div class="lbl">Pengajuan Selesai</div><div class="dsc">Telah selesai diproses</div></div></div>
    </section>

    <h2 class="section-title">Menu Utama</h2>
    <section class="quick-grid">
        <button type="button" class="quick" data-open="#modalAjukan"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
            <div class="ico"><x-icon name="send" :size="24" /></div><h3>Ajukan Layanan</h3><p>Ajukan peminjaman rapor, legalisir, atau pengambilan dokumen akademik.</p></button>
        <a href="{{ route('siswa.dashboard', ['halaman' => 'riwayat']) }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
            <div class="ico"><x-icon name="clock" :size="24" /></div><h3>Riwayat Pengajuan</h3><p>Lihat daftar pengajuan, status layanan, tanggal pinjam, dan batas pengembalian rapor.</p></a>
        <a href="{{ route('siswa.dokumen') }}" class="quick"><span class="go"><x-icon name="arrow-right" :size="20" /></span>
            <div class="ico"><x-icon name="folder" :size="24" /></div><h3>Dokumen Saya</h3><p>Lihat rapor, sertifikat, dan dokumen akademik pribadimu yang sudah terdaftar.</p></a>
    </section>

    <div class="callout"><x-icon name="info" :size="20" /><div><strong>Informasi:</strong> Gunakan menu Riwayat Pengajuan untuk melihat status layanan dokumen akademik yang tercatat atas akunmu. Jika data belum sesuai, hubungi Tata Usaha sekolah.</div></div>
@else
    <div class="page-head">
        <div><h1>Riwayat Pengajuan</h1><p>Berikut daftar pengajuan layanan dokumen akademik yang tercatat untuk akunmu.</p></div>
        <button type="button" class="btn btn-primary" data-open="#modalAjukan"><x-icon name="plus" :size="18" /> Ajukan Layanan</button>
    </div>

    <section class="stat-grid">
        <div class="stat"><span class="ico violet"><x-icon name="file" :size="26" /></span><div><div class="num">{{ $jmlTotal }}</div><div class="lbl">Total Pengajuan</div><div class="dsc">Seluruh pengajuan layanan</div></div></div>
        <div class="stat"><span class="ico amber"><x-icon name="clock" :size="26" /></span><div><div class="num">{{ $jmlPending }}</div><div class="lbl">Menunggu Persetujuan</div><div class="dsc">Menunggu keputusan Tata Usaha</div></div></div>
        <div class="stat"><span class="ico green"><x-icon name="check-circle" :size="26" /></span><div><div class="num">{{ $jmlSelesai }}</div><div class="lbl">Pengajuan Selesai</div><div class="dsc">Layanan yang telah diselesaikan</div></div></div>
    </section>

    <div class="card">
        <div class="card-head"><div><h2>Riwayat Layanan Dokumen</h2><p>Periksa status dan informasi peminjaman rapor pada tabel berikut.</p></div></div>
        <div class="table-wrap">
            <table class="table stack">
                <thead>
                    <tr><th>No.</th><th>Jenis Layanan</th><th>Tanggal Pengajuan</th><th>Status</th><th>Catatan</th><th>Jadwal Layanan</th></tr>
                </thead>
                <tbody>
                @forelse ($pengajuan as $item)
                    <tr>
                        <td class="num">{{ $loop->iteration }}</td>
                        <td class="td-main"><span class="cell-main">{{ $item->jenis_layanan }}</span></td>
                        <td data-label="Diajukan">{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d/m/Y') : '-' }}</td>
                        <td data-label="Status"><span class="badge badge-{{ strtolower($item->status) }}">{{ $item->status }}</span></td>
                        <td data-label="Catatan">
                            @if ($item->status === 'Ditolak')
                                <div class="note-reject"><b>Alasan penolakan</b>{{ $item->catatan ?: 'Admin belum memberikan alasan penolakan.' }}</div>
                            @else - @endif
                        </td>
                        <td data-label="Jadwal">
                            @php $jadwal = $item->jadwal(); @endphp
                            @if (count($jadwal))
                                <div class="jadwal">@foreach ($jadwal as [$lbl, $tgl])<span><b>{{ $lbl }}:</b>{{ $tgl }}</span>@endforeach</div>
                            @else - @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><div class="ico"><x-icon name="file" :size="28" /></div><strong>Belum ada pengajuan layanan</strong>Klik tombol “Ajukan Layanan” untuk memulai.<div style="margin-top:16px"><button type="button" class="btn btn-primary btn-sm" data-open="#modalAjukan">Ajukan Layanan</button></div></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="callout"><x-icon name="info" :size="20" /><div><strong>Informasi:</strong> Untuk peminjaman rapor, perhatikan tanggal pinjam dan batas pengembalian. Hubungi Tata Usaha jika membutuhkan informasi lebih lanjut.</div></div>
@endif
@endsection

@push('modals')
@php $mo = $errors->any() && old('_modal') === 'ajukan'; @endphp
<div class="modal dlg-wrap" id="modalAjukan" role="dialog" aria-modal="true" aria-labelledby="ttlAjukan" @if($mo) data-autoopen @endif>
    <div class="dlg lg">
        <form action="{{ route('siswa.pengajuan.store') }}" method="POST" data-once data-service-form>
            @csrf
            <input type="hidden" name="_modal" value="ajukan">
            <div class="dlg-head">
                <span class="ico"><x-icon name="send" :size="22" /></span>
                <div><h3 id="ttlAjukan">Ajukan Layanan</h3><p>Isi formulir untuk mengajukan layanan akademik.</p></div>
                <button type="button" class="dlg-x" data-close aria-label="Tutup"><x-icon name="x" :size="18" /></button>
            </div>
            <div class="dlg-body">
                @if ($mo)
                    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
                @endif
                <div class="field">
                    <label for="a_jenis">Jenis Layanan <span class="req">*</span></label>
                    <select name="jenis_layanan" id="a_jenis" data-service-select required>
                        <option value="">-- Pilih layanan --</option>
                        @foreach (\App\Models\Pengajuan::LAYANAN as $opt)
                            <option value="{{ $opt }}" {{ $mo && old('jenis_layanan') == $opt ? 'selected' : '' }}>{{ $opt === 'Lainnya' ? 'Dokumen Lainnya' : $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="a_detail">Detail Pengajuan</label>
                    <textarea name="detail" id="a_detail" rows="4" maxlength="1000" placeholder="Tuliskan keperluan atau keterangan tambahan...">{{ $mo ? old('detail') : '' }}</textarea>
                    <span class="hint">Contoh: dokumen yang akan dilegalisir atau alasan meminjam rapor.</span>
                </div>
                @include('partials.pengajuan-blocks', ['uid' => 'sj', 'useOld' => $mo, 'minToday' => true])
                <div class="callout" style="margin:0"><x-icon name="info" :size="20" /><div>Tanggal pengajuan tercatat otomatis. Setelah dikirim, status pengajuanmu <b>Pending</b> hingga dikonfirmasi Tata Usaha.</div></div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary"><x-icon name="send" :size="17" /> Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endpush

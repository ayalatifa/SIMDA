@extends('layouts.app')
@section('title', 'Konfirmasi Pengajuan')

@section('content')
@php
    $jmlPending = $pengajuan->where('status', 'Pending')->count();
    $jmlSetuju = $pengajuan->where('status', 'Disetujui')->count();
    $jmlTolak = $pengajuan->where('status', 'Ditolak')->count();
    $defaultChip = $jmlPending > 0 ? 'Pending' : '';
@endphp

<div class="page-head">
    <div><h1>Konfirmasi Pengajuan Siswa</h1><p>Periksa pengajuan yang masuk, lalu setujui atau tolak.</p></div>
</div>

@if ($errors->any())
    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div><strong>Keputusan belum tersimpan.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
@endif

<div class="card">
    <div class="chips" role="tablist" aria-label="Filter status">
        <button type="button" class="chip {{ $defaultChip === '' ? 'active' : '' }}" data-chip="">Semua <span class="n">{{ $pengajuan->count() }}</span></button>
        <button type="button" class="chip {{ $defaultChip === 'Pending' ? 'active' : '' }}" data-chip="Pending">Menunggu <span class="n">{{ $jmlPending }}</span></button>
        <button type="button" class="chip" data-chip="Disetujui">Disetujui <span class="n">{{ $jmlSetuju }}</span></button>
        <button type="button" class="chip" data-chip="Ditolak">Ditolak <span class="n">{{ $jmlTolak }}</span></button>
    </div>
    <div class="toolbar">
        <div class="search-box"><x-icon name="search" :size="18" />
            <input type="search" placeholder="Cari nama siswa, kelas, atau layanan..." data-live-search="#reqList" aria-label="Cari pengajuan"></div>
        <span class="result-info" data-result-info></span>
    </div>

    <div class="req-list" id="reqList">
        @forelse ($pengajuan as $item)
            @php $statusSlug = strtolower(str_replace(' ', '-', $item->status)); @endphp
            <article class="req" data-search-item data-status="{{ $item->status }}">
                <div class="req-top">
                    <div class="cell-person">
                        <span class="avatar">{{ strtoupper(mb_substr($item->siswa?->nama_lengkap ?? '?', 0, 1)) }}</span>
                        <div>
                            <div class="cell-main">{{ $item->siswa?->nama_lengkap ?? 'Data siswa tidak ditemukan' }}</div>
                            <div class="cell-sub">Kelas {{ $item->siswa?->kelas ?? '-' }}</div>
                        </div>
                    </div>
                    <span class="badge badge-{{ $statusSlug }}">{{ $item->status }}</span>
                    <div class="req-meta" style="width:100%">
                        <div>Jenis Layanan<b>{{ $item->jenis_layanan }}</b></div>
                        <div>Tanggal<b>{{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') : '-' }}</b></div>
                    </div>
                </div>

                <div class="req-detail"><b style="display:block;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px">Detail</b>{{ $item->detail ?: '-' }}</div>

                @if ($item->status === 'Ditolak' && $item->catatan)
                    <div class="note-reject" style="margin:0 20px 16px"><b>Alasan penolakan</b>{{ $item->catatan }}</div>
                @endif

                <form class="req-form action-form" action="{{ route('admin.pengajuan.konfirmasi.update', $item->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="field">
                            <label for="status-{{ $item->id }}">Keputusan</label>
                            <select name="status" id="status-{{ $item->id }}" class="status-select" required>
                                <option value="Disetujui" {{ old('status') === 'Disetujui' && $errors->any() ? 'selected' : ($item->status === 'Disetujui' ? 'selected' : '') }}>Setujui</option>
                                <option value="Ditolak" {{ old('status') === 'Ditolak' && $errors->any() ? 'selected' : ($item->status === 'Ditolak' ? 'selected' : '') }}>Tolak</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><x-icon name="check" :size="17" /> Simpan Keputusan</button>
                    </div>
                    <div class="field catatan-field">
                        <label for="catatan-{{ $item->id }}">Alasan Penolakan</label>
                        <textarea name="catatan" id="catatan-{{ $item->id }}" rows="3" maxlength="1000" placeholder="Tuliskan alasan pengajuan ditolak...">{{ old('catatan', $item->status === 'Ditolak' ? $item->catatan : '') }}</textarea>
                        <span class="hint">Wajib diisi jika pengajuan ditolak.</span>
                    </div>
                </form>
            </article>
        @empty
            <div class="empty-state"><div class="ico"><x-icon name="clipboard" :size="28" /></div><strong>Belum ada pengajuan</strong>Pengajuan dari siswa akan tampil di sini.</div>
        @endforelse
        <div class="empty-state" data-search-empty hidden><div class="ico"><x-icon name="search" :size="28" /></div><strong>Tidak ada pengajuan yang cocok</strong>Ubah filter atau kata kunci pencarian.</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.action-form').forEach(function (form) {
    var status = form.querySelector('.status-select');
    var field = form.querySelector('.catatan-field');
    var catatan = field.querySelector('textarea');
    function toggle() {
        var ditolak = status.value === 'Ditolak';
        field.style.display = ditolak ? 'block' : 'none';
        catatan.required = ditolak;
        if (!ditolak) catatan.value = '';
    }
    status.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush

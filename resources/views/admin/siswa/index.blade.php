@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')
<div class="page-head">
    <div><h1>Data Siswa</h1><p>Kelola data siswa yang terdaftar dalam sistem akademik.</p></div>
    <button type="button" class="btn btn-primary" data-open="#modalSiswa"><x-icon name="plus" :size="18" /> Tambah Siswa</button>
</div>

<div class="card">
    <div class="card-head"><div><h2>Daftar Siswa</h2><p>Data identitas siswa yang tersimpan dalam sistem.</p></div></div>

    <form action="{{ route('admin.siswa.index') }}" method="GET" class="toolbar" id="studentSearchForm" role="search">
        <div class="search-box">
            <x-icon name="search" :size="18" />
            <input type="text" name="search" id="studentSearchInput" placeholder="Cari nama siswa, NIS, atau NISN..." value="{{ $keyword ?? request('search') }}" autocomplete="off">
            @if (request('search'))
                <a href="{{ route('admin.siswa.index') }}" class="clear" title="Hapus pencarian" aria-label="Hapus pencarian"><x-icon name="x" :size="14" /></a>
            @endif
        </div>
        <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
        @if ($siswa->total() > 0)<span class="result-info">{{ $siswa->total() }} siswa</span>@endif
    </form>

    <div class="table-wrap">
        <table class="table stack">
            <thead>
                <tr><th>No</th><th>Nama Siswa</th><th>NISN</th><th>NIS</th><th>Kelas</th><th>No. HP</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse ($siswa as $item)
                <tr>
                    <td class="num">{{ $siswa->firstItem() + $loop->index }}</td>
                    <td class="td-main" data-label="Nama">
                        <div class="cell-person">
                            <span class="avatar">{{ strtoupper(mb_substr($item->nama_lengkap, 0, 1)) }}</span>
                            <div><div class="cell-main">{{ $item->nama_lengkap }}</div><div class="cell-sub">{{ $item->user_id ? 'Akun aktif' : 'Belum registrasi' }}</div></div>
                        </div>
                    </td>
                    <td data-label="NISN">{{ $item->nisn }}</td>
                    <td data-label="NIS">{{ $item->nis }}</td>
                    <td data-label="Kelas"><span class="tag">{{ $item->kelas }}</span></td>
                    <td data-label="No. HP">{{ $item->no_hp ?? '-' }}</td>
                    <td class="td-actions">
                        <div class="kebab">
                            <button type="button" class="kebab-btn" aria-haspopup="menu" aria-expanded="false" aria-label="Aksi"><x-icon name="more" :size="18" /></button>
                            <div class="kebab-menu" role="menu">
                                <a href="{{ route('admin.siswa.edit', $item) }}" class="kebab-item" role="menuitem"><x-icon name="edit" :size="16" /> Edit</a>
                                <form action="{{ route('admin.siswa.destroy', $item) }}" method="POST" data-confirm="Yakin ingin menghapus data siswa {{ $item->nama_lengkap }}? Tindakan ini tidak dapat dibatalkan.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="kebab-item danger" role="menuitem"><x-icon name="trash" :size="16" /> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">
                    <div class="empty-state"><div class="ico"><x-icon name="users" :size="28" /></div>
                        <strong>{{ request('search') ? 'Siswa tidak ditemukan' : 'Belum ada data siswa' }}</strong>
                        {{ request('search') ? 'Coba kata kunci lain.' : 'Klik “Tambah Siswa” untuk menambahkan data pertama.' }}
                    </div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $siswa->links('vendor.pagination.simda') }}
</div>
@endsection

@push('modals')
<div class="modal dlg-wrap" id="modalSiswa" role="dialog" aria-modal="true" aria-labelledby="ttlSiswa" @if($errors->any() && old('_modal') === 'siswa') data-autoopen @endif>
    <div class="dlg">
        <form action="{{ route('admin.siswa.store') }}" method="POST" data-once>
            @csrf
            <input type="hidden" name="_modal" value="siswa">
            <div class="dlg-head">
                <span class="ico"><x-icon name="users" :size="22" /></span>
                <div><h3 id="ttlSiswa">Tambah Siswa</h3><p>Tambahkan data siswa baru ke dalam sistem.</p></div>
                <button type="button" class="dlg-x" data-close aria-label="Tutup"><x-icon name="x" :size="18" /></button>
            </div>
            <div class="dlg-body">
                @if ($errors->any() && old('_modal') === 'siswa')
                    <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
                @endif
                <div class="form-grid">
                    <div class="field full"><label for="m_nama">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="m_nama" name="nama_lengkap" value="{{ old('_modal') === 'siswa' ? old('nama_lengkap') : '' }}" placeholder="Masukkan nama lengkap siswa" required></div>
                    <div class="field"><label for="m_nisn">NISN <span class="req">*</span></label>
                        <input type="text" id="m_nisn" name="nisn" value="{{ old('_modal') === 'siswa' ? old('nisn') : '' }}" placeholder="Masukkan NISN" required></div>
                    <div class="field"><label for="m_nis">NIS <span class="req">*</span></label>
                        <input type="text" id="m_nis" name="nis" value="{{ old('_modal') === 'siswa' ? old('nis') : '' }}" placeholder="Masukkan NIS" required></div>
                    <div class="field full"><label for="m_kelas">Kelas <span class="req">*</span></label>
                        <input type="text" id="m_kelas" name="kelas" value="{{ old('_modal') === 'siswa' ? old('kelas') : '' }}" placeholder="Contoh: XII RPL 1" required>
                        <span class="hint">NIS digunakan siswa saat registrasi akun, pastikan data benar.</span></div>
                </div>
            </div>
            <div class="dlg-foot">
                <button type="button" class="btn btn-secondary" data-close>Batal</button>
                <button type="submit" class="btn btn-primary"><x-icon name="check" :size="18" /> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
(function () {
    var input = document.getElementById('studentSearchInput'), form = document.getElementById('studentSearchForm'), t;
    if (!input || !form) return;
    input.addEventListener('input', function () {
        clearTimeout(t);
        t = setTimeout(function () { sessionStorage.setItem('focusStudentSearch', '1'); form.requestSubmit(); }, 800);
    });
    if (sessionStorage.getItem('focusStudentSearch') === '1') {
        sessionStorage.removeItem('focusStudentSearch');
        input.focus(); input.setSelectionRange(input.value.length, input.value.length);
    }
})();
</script>
@endpush

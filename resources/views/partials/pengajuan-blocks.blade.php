{{--
    Blok informasi per jenis layanan. Semua blok selalu tampil,
    tetapi hanya blok sesuai layanan terpilih yang aktif (diatur oleh JS: data-service-form).

    Parameter:
      $uid      awalan id unik
      $edit     true bila dipakai di form edit admin
      $p        model Pengajuan (edit)
      $useOld   true bila isian harus diisi ulang dari old() setelah error validasi
      $minToday true bila tanggal tidak boleh sebelum hari ini (form siswa)
--}}
@php
    $uid = $uid ?? 'pg';
    $edit = $edit ?? false;
    $p = $p ?? null;
    $useOld = $useOld ?? false;
    $minToday = $minToday ?? false;
    $katNow = $p?->kategori;

    $locked = fn (string $kolom, string $kat) => $edit && $p && $katNow === $kat && $p->{$kolom} !== null;
    $val = function (string $kolom, string $kat) use ($edit, $p, $katNow, $useOld, $locked) {
        if ($locked($kolom, $kat)) {
            return $p->{$kolom}->format('Y-m-d');
        }
        if ($useOld && old($kolom) !== null) {
            return old($kolom);
        }
        if ($edit && $p && $katNow === $kat && $p->{$kolom}) {
            return $p->{$kolom}->format('Y-m-d');
        }
        return '';
    };
    $min = $minToday ? 'min=' . date('Y-m-d') : '';
    $batasAwal = ($edit && $p && $katNow === 'rapor' && $p->batas_pengembalian) ? $p->batas_pengembalian->format('d/m/Y') : '';
@endphp

<div class="svc-grid">
    {{-- ===== Peminjaman Rapor ===== --}}
    <fieldset class="svc-block" data-block="rapor" disabled>
        <div class="svc-title"><span class="ico"><x-icon name="book" :size="17" /></span> Informasi Peminjaman Rapor <span class="svc-off">Tidak aktif</span></div>
        @php $lk = $locked('tanggal_pinjam', 'rapor'); @endphp
        <div class="field">
            <label for="{{ $uid }}_pinjam">Tanggal Pinjam @unless($lk)<span class="req">*</span>@endunless</label>
            <input type="date" id="{{ $uid }}_pinjam" name="tanggal_pinjam" value="{{ $val('tanggal_pinjam', 'rapor') }}" data-pinjam {{ $min }} @if($lk) readonly @else required @endif>
            <span class="hint">{{ $lk ? 'Sudah diisi dan tidak dapat diubah.' : 'Diisi sendiri saat mengajukan; tidak dapat diubah setelah disimpan.' }}</span>
        </div>
        <div class="field">
            <label for="{{ $uid }}_batas">Batas Pengembalian</label>
            <input type="text" id="{{ $uid }}_batas" value="{{ $batasAwal }}" placeholder="Otomatis" data-batas readonly>
            <span class="hint">Otomatis 7 hari setelah tanggal pinjam.</span>
        </div>
        @if ($edit)
            <div class="field">
                <label for="{{ $uid }}_kembali_r">Tanggal Pengembalian</label>
                <input type="date" id="{{ $uid }}_kembali_r" name="tanggal_pengembalian" value="{{ $useOld && old('tanggal_pengembalian') !== null ? old('tanggal_pengembalian') : ($katNow === 'rapor' && $p?->tanggal_pengembalian ? $p->tanggal_pengembalian->format('Y-m-d') : '') }}">
            </div>
        @endif
    </fieldset>

    {{-- ===== Legalisir ===== --}}
    <fieldset class="svc-block" data-block="legalisir" disabled>
        <div class="svc-title"><span class="ico"><x-icon name="badge" :size="17" /></span> Informasi Legalisir <span class="svc-off">Tidak aktif</span></div>
        @php $lk = $locked('tanggal_legalisir', 'legalisir'); @endphp
        <div class="field">
            <label for="{{ $uid }}_legalisir">Tanggal Legalisir @unless($lk)<span class="req">*</span>@endunless</label>
            <input type="date" id="{{ $uid }}_legalisir" name="tanggal_legalisir" value="{{ $val('tanggal_legalisir', 'legalisir') }}" {{ $min }} @if($lk) readonly @else required @endif>
            <span class="hint">{{ $lk ? 'Sudah diisi dan tidak dapat diubah.' : 'Diisi sendiri saat mengajukan; tidak dapat diubah setelah disimpan.' }}</span>
        </div>
        @if ($edit)
            <div class="field">
                <label for="{{ $uid }}_kembali_l">Tanggal Pengembalian</label>
                <input type="date" id="{{ $uid }}_kembali_l" name="tanggal_pengembalian" value="{{ $useOld && old('tanggal_pengembalian') !== null ? old('tanggal_pengembalian') : ($katNow === 'legalisir' && $p?->tanggal_pengembalian ? $p->tanggal_pengembalian->format('Y-m-d') : '') }}">
            </div>
        @endif
    </fieldset>

    {{-- ===== Pengambilan Ijazah ===== --}}
    <fieldset class="svc-block" data-block="ijazah" disabled>
        <div class="svc-title"><span class="ico"><x-icon name="package" :size="17" /></span> Informasi Pengambilan Ijazah <span class="svc-off">Tidak aktif</span></div>
        @php $lk = $locked('tanggal_pengambilan', 'ijazah'); @endphp
        <div class="field">
            <label for="{{ $uid }}_ambil_i">Tanggal Pengambilan @unless($lk)<span class="req">*</span>@endunless</label>
            <input type="date" id="{{ $uid }}_ambil_i" name="tanggal_pengambilan" value="{{ $val('tanggal_pengambilan', 'ijazah') }}" {{ $min }} @if($lk) readonly @else required @endif>
            <span class="hint">{{ $lk ? 'Sudah diisi dan tidak dapat diubah.' : 'Diisi sendiri saat mengajukan; tidak dapat diubah setelah disimpan.' }}</span>
        </div>
    </fieldset>

    {{-- ===== Dokumen Lainnya ===== --}}
    <fieldset class="svc-block" data-block="lainnya" disabled>
        <div class="svc-title"><span class="ico"><x-icon name="folder" :size="17" /></span> Informasi Dokumen Lainnya <span class="svc-off">Tidak aktif</span></div>
        @php $lk = $locked('tanggal_pengambilan', 'lainnya'); @endphp
        <div class="field">
            <label for="{{ $uid }}_ambil_l">Tanggal Pengambilan @unless($lk)<span class="req">*</span>@endunless</label>
            <input type="date" id="{{ $uid }}_ambil_l" name="tanggal_pengambilan" value="{{ $val('tanggal_pengambilan', 'lainnya') }}" {{ $min }} @if($lk) readonly @else required @endif>
            <span class="hint">{{ $lk ? 'Sudah diisi dan tidak dapat diubah.' : 'Diisi sendiri saat mengajukan; tidak dapat diubah setelah disimpan.' }}</span>
        </div>
    </fieldset>
</div>

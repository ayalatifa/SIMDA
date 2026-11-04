@php
    $notifItems = \App\Models\Pengajuan::with('siswa')->where('status', 'Pending')->latest()->get();
    $notifError = $errors->any() && old('_modal') === 'notif';
@endphp
<div class="modal dlg-wrap" id="notifModal" role="dialog" aria-modal="true" aria-labelledby="notifTitle" @if($notifError) data-autoopen @endif>
    <div class="dlg lg">
        <div class="dlg-head">
            <span class="ico amber"><x-icon name="bell" :size="22" /></span>
            <div>
                <h3 id="notifTitle">Konfirmasi Pengajuan</h3>
                <p><span id="notifCount">{{ $notifItems->count() }}</span> pengajuan menunggu keputusan Anda</p>
            </div>
            <button type="button" class="dlg-x" data-close aria-label="Tutup"><x-icon name="x" :size="18" /></button>
        </div>
        <div class="dlg-body" id="notifBody">
            @if ($notifError)
                <div class="alert alert-error" role="alert"><x-icon name="alert" :size="18" /><div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div></div>
            @endif

            @forelse ($notifItems as $item)
                <form class="notif-item" action="{{ route('admin.pengajuan.konfirmasi.update', $item->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="_modal" value="notif">
                    <div class="notif-top">
                        <div class="cell-person">
                            <span class="avatar">{{ strtoupper(mb_substr($item->siswa?->nama_lengkap ?? '?', 0, 1)) }}</span>
                            <div>
                                <div class="cell-main">{{ $item->siswa?->nama_lengkap ?? 'Data siswa tidak ditemukan' }}</div>
                                <div class="cell-sub">Kelas {{ $item->siswa?->kelas ?? '-' }}</div>
                            </div>
                        </div>
                        <span class="badge badge-pending">Pending</span>
                    </div>
                    <div class="notif-meta">
                        <span class="tag">{{ $item->jenis_layanan }}</span>
                        <span><x-icon name="calendar" :size="14" /> Diajukan {{ $item->tanggal_pengajuan ? \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') : '-' }}</span>
                        @foreach ($item->jadwal() as [$lbl, $tgl])
                            <span class="tag tag-amber">{{ $lbl }}: {{ $tgl }}</span>
                        @endforeach
                    </div>
                    @if ($item->detail)<div class="notif-detail">{{ $item->detail }}</div>@endif

                    <div class="notif-actions">
                        <button type="submit" name="status" value="Disetujui" class="btn btn-ok btn-sm" formnovalidate><x-icon name="check" :size="16" /> Setujui</button>
                        <button type="button" class="btn btn-danger-soft btn-sm" data-reject-open><x-icon name="x" :size="16" /> Tolak</button>
                    </div>

                    <div class="reject-box" hidden>
                        <label for="catatan-{{ $item->id }}">Catatan penolakan <span style="color:var(--err)">*</span></label>
                        <textarea id="catatan-{{ $item->id }}" name="catatan" maxlength="1000" placeholder="Tuliskan alasan pengajuan ditolak..." disabled></textarea>
                        <div class="acts">
                            <button type="button" class="btn btn-secondary btn-sm" data-reject-cancel>Batal</button>
                            <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-sm">Kirim Penolakan</button>
                        </div>
                    </div>
                </form>
            @empty
                <div class="empty-state"><div class="ico"><x-icon name="check-circle" :size="28" /></div><strong>Tidak ada pengajuan menunggu</strong>Semua pengajuan sudah dikonfirmasi. Hasilnya dapat dilihat di menu Pengajuan Layanan.</div>
            @endforelse
        </div>
    </div>
</div>

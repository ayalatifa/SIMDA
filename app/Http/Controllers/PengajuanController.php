<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengajuanController extends Controller
{
    public function index(Request $request)
{
    $query = Pengajuan::with('siswa')->latest();

    if ($request->filled('jenis_layanan')) {
        $query->kategori(Pengajuan::kategoriDari($request->jenis_layanan));
    }

    $pengajuan = $query->get();

    return view('admin.pengajuan.index', compact('pengajuan'));
}

    public function create()
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();

        return view('admin.pengajuan.create', compact('siswa'));
    }

    
public function store(Request $request)
{
    $kategori = Pengajuan::kategoriDari($request->input('jenis_layanan'));

    $validated = $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'jenis_layanan' => ['required', Rule::in(Pengajuan::LAYANAN)],
        'detail' => 'nullable|string',
        'tanggal_pinjam' => [Rule::requiredIf($kategori === 'rapor'), 'nullable', 'date'],
        'tanggal_legalisir' => [Rule::requiredIf($kategori === 'legalisir'), 'nullable', 'date'],
        'tanggal_pengambilan' => [Rule::requiredIf(in_array($kategori, ['ijazah', 'lainnya'])), 'nullable', 'date'],
    ], [
        'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
        'tanggal_legalisir.required' => 'Tanggal legalisir wajib diisi.',
        'tanggal_pengambilan.required' => 'Tanggal pengambilan wajib diisi.',
    ]);

    Pengajuan::create([
        'siswa_id' => $validated['siswa_id'],
        'jenis_layanan' => $validated['jenis_layanan'],
        'detail' => $validated['detail'] ?? null,
        'status' => 'Pending',
        // Tanggal pengajuan tercatat otomatis saat pengajuan dibuat.
        'tanggal_pengajuan' => now()->toDateString(),
    ] + Pengajuan::tanggalLayananBaru($kategori, $validated));

    return redirect()
        ->route('admin.pengajuan.index')
        ->with('success', 'Pengajuan berhasil ditambahkan.');
}


    public function edit(Pengajuan $pengajuan)
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();

        return view('admin.pengajuan.edit', compact('pengajuan', 'siswa'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $kategori = Pengajuan::kategoriDari($request->input('jenis_layanan'));
        $kategoriLama = $pengajuan->kategori;
        $sama = $kategori === $kategoriLama;

        // Tanggal layanan yang sudah pernah diisi tidak dapat diubah lagi.
        $terkunci = fn (string $kolom) => $sama && $pengajuan->{$kolom} !== null;

        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_layanan' => ['required', Rule::in(array_merge(Pengajuan::LAYANAN, [$pengajuan->jenis_layanan]))],
            'detail' => 'nullable|string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Pending,Disetujui,Ditolak,Dipinjam,Dikembalikan,Selesai',
            'tanggal_pinjam' => [Rule::requiredIf($kategori === 'rapor' && !$terkunci('tanggal_pinjam')), 'nullable', 'date'],
            'tanggal_legalisir' => [Rule::requiredIf($kategori === 'legalisir' && !$terkunci('tanggal_legalisir')), 'nullable', 'date'],
            'tanggal_pengambilan' => [Rule::requiredIf(in_array($kategori, ['ijazah', 'lainnya']) && !$terkunci('tanggal_pengambilan')), 'nullable', 'date'],
            'tanggal_pengembalian' => 'nullable|date',
        ], [
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_legalisir.required' => 'Tanggal legalisir wajib diisi.',
            'tanggal_pengambilan.required' => 'Tanggal pengambilan wajib diisi.',
        ]);

        $data = [
            'siswa_id' => $validated['siswa_id'],
            'jenis_layanan' => $validated['jenis_layanan'],
            'detail' => $validated['detail'] ?? null,
            'status' => $validated['status'],
            // tanggal_pengajuan sengaja tidak diubah (otomatis).
            'tanggal_pinjam' => null,
            'batas_pengembalian' => null,
            'tanggal_pengembalian' => null,
            'tanggal_legalisir' => null,
            'tanggal_pengambilan' => null,
        ];

        if (array_key_exists('catatan', $validated)) {
            $data['catatan'] = $validated['catatan'];
        }

        switch ($kategori) {
            case 'rapor':
                $data['tanggal_pinjam'] = $terkunci('tanggal_pinjam')
                    ? $pengajuan->tanggal_pinjam->toDateString()
                    : ($validated['tanggal_pinjam'] ?? null);
                $data['batas_pengembalian'] = Pengajuan::hitungBatas($data['tanggal_pinjam']);
                $data['tanggal_pengembalian'] = $validated['tanggal_pengembalian'] ?? null;
                break;

            case 'legalisir':
                $data['tanggal_legalisir'] = $terkunci('tanggal_legalisir')
                    ? $pengajuan->tanggal_legalisir->toDateString()
                    : ($validated['tanggal_legalisir'] ?? null);
                $data['tanggal_pengembalian'] = $validated['tanggal_pengembalian'] ?? null;
                break;

            default: // ijazah & lainnya
                $data['tanggal_pengambilan'] = $terkunci('tanggal_pengambilan')
                    ? $pengajuan->tanggal_pengambilan->toDateString()
                    : ($validated['tanggal_pengambilan'] ?? null);
        }

        $pengajuan->update($data);

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function laporan()
{
    $total = Pengajuan::count();

    $pending = Pengajuan::where('status', 'Pending')->count();
    $disetujui = Pengajuan::where('status', 'Disetujui')->count();
    $ditolak = Pengajuan::where('status', 'Ditolak')->count();
    $dipinjam = Pengajuan::where('status', 'Dipinjam')->count();
    $dikembalikan = Pengajuan::where('status', 'Dikembalikan')->count();
    $selesai = Pengajuan::where('status', 'Selesai')->count();

    $peminjamanRapor = Pengajuan::kategori('rapor')->count();
    $legalisir = Pengajuan::kategori('legalisir')->count();
    $pengambilanIjazah = Pengajuan::kategori('ijazah')->count();
    $dokumenLainnya = Pengajuan::kategori('lainnya')->count();

    return view('admin.laporan.index', compact(
        'total',
        'pending',
        'disetujui',
        'ditolak',
        'dipinjam',
        'dikembalikan',
        'selesai',
        'peminjamanRapor',
        'legalisir',
        'pengambilanIjazah',
        'dokumenLainnya'
    ));
}


    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}
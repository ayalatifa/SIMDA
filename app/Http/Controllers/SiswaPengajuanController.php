<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaPengajuanController extends Controller
{
    public function index()
{
    $siswa = \App\Models\Siswa::where(
        'user_id',
        auth()->id()
    )->firstOrFail();

    $pengajuan = \App\Models\Pengajuan::where(
        'siswa_id',
        $siswa->id
    )->latest()->get();

    return view('siswa.pengajuan.index', compact('pengajuan'));
}

    public function create()
    {
        return view('siswa.pengajuan.create');
    }

    public function store(Request $request)
    {
        $kategori = Pengajuan::kategoriDari($request->input('jenis_layanan'));

        $validated = $request->validate([
            'jenis_layanan' => ['required', Rule::in(Pengajuan::LAYANAN)],
            'detail' => 'nullable|string|max:1000',
            'tanggal_pinjam' => [Rule::requiredIf($kategori === 'rapor'), 'nullable', 'date', 'after_or_equal:today'],
            'tanggal_legalisir' => [Rule::requiredIf($kategori === 'legalisir'), 'nullable', 'date', 'after_or_equal:today'],
            'tanggal_pengambilan' => [Rule::requiredIf(in_array($kategori, ['ijazah', 'lainnya'])), 'nullable', 'date', 'after_or_equal:today'],
        ], [
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_legalisir.required' => 'Tanggal legalisir wajib diisi.',
            'tanggal_pengambilan.required' => 'Tanggal pengambilan wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tanggal_legalisir.after_or_equal' => 'Tanggal legalisir tidak boleh sebelum hari ini.',
            'tanggal_pengambilan.after_or_equal' => 'Tanggal pengambilan tidak boleh sebelum hari ini.',
        ]);

        $siswa = Siswa::where('user_id', auth()->id())
            ->firstOrFail();

        Pengajuan::create([
            'siswa_id' => $siswa->id,
            'jenis_layanan' => $validated['jenis_layanan'],
            'detail' => $validated['detail'] ?? null,
            'catatan' => null,
            // Tanggal pengajuan tercatat otomatis saat pengajuan dibuat.
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'Pending',
        ] + Pengajuan::tanggalLayananBaru($kategori, $validated));

        return redirect()
            ->route('siswa.dashboard', ['halaman' => 'riwayat'])
            ->with('success', 'Pengajuan layanan berhasil dikirim.');
    }
}

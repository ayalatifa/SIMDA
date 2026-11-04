<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class AdminPengajuanController extends Controller
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

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $data = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        if ($data['status'] === 'Ditolak') {
            $request->validate([
                'catatan' => 'required|string|max:1000',
            ]);
        }

        $pengajuan->status = $data['status'];

        // Catatan hanya disimpan jika pengajuan ditolak.
        $pengajuan->catatan = $data['status'] === 'Ditolak'
            ? trim($data['catatan'])
            : null;

        $pengajuan->save();

        return redirect()
            ->route('admin.pengajuan.konfirmasi')
            ->with('success', 'Keputusan pengajuan berhasil disimpan.');
    }
}

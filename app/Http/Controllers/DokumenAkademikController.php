<?php

namespace App\Http\Controllers;

use App\Models\DokumenAkademik;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenAkademikController extends Controller
{
    public function index()
    {
        $dokumen = DokumenAkademik::with('siswa')
            ->latest()
            ->get();

        return view('admin.dokumen.index', compact('dokumen'));
    }

    public function create()
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();

        return view('admin.dokumen.create', compact('siswa'));
    }

    
public function store(Request $request)
{
    $validated = $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'jenis_dokumen' => 'required|in:Rapor,Ijazah,SKL,Sertifikat,Lainnya,Legalisir,Dokumen Akademik',
        'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'keterangan' => 'nullable|string',
    ]);

    if ($request->hasFile('file_dokumen')) {
        $path = $request->file('file_dokumen')
            ->store('dokumen-akademik', 'public');

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'file_dokumen' => 'File gagal disimpan.',
                ]);
        }

        $validated['file_dokumen'] = $path;
    }

    DokumenAkademik::create($validated);

    return redirect()
        ->route('admin.dokumen.index')
        ->with('success', 'Dokumen akademik berhasil ditambahkan.');
}


    public function edit(DokumenAkademik $dokumen)
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();

        return view('admin.dokumen.edit', compact('dokumen', 'siswa'));
    }

    public function update(Request $request, DokumenAkademik $dokumen)
{
    $validated = $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'jenis_dokumen' => 'required|in:Rapor,Ijazah,SKL,Sertifikat,Lainnya,Legalisir,Dokumen Akademik',
        'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'keterangan' => 'nullable|string',
    ]);

    if ($request->hasFile('file_dokumen')) {
        if ($dokumen->file_dokumen) {
            Storage::disk('public')->delete($dokumen->file_dokumen);
        }

        $validated['file_dokumen'] = $request
            ->file('file_dokumen')
            ->store('dokumen-akademik', 'public');
    }

    $dokumen->update($validated);

    return redirect()
        ->route('admin.dokumen.index')
        ->with('success', 'Dokumen akademik berhasil diperbarui.');
}

    public function destroy(DokumenAkademik $dokumen)
    {
        if ($dokumen->file_dokumen) {
            Storage::disk('public')->delete($dokumen->file_dokumen);
        }

        $dokumen->delete();

        return redirect()
            ->route('admin.dokumen.index')
            ->with('success', 'Dokumen akademik berhasil dihapus.');
    }
}
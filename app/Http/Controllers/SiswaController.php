<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
{
    $keyword = $request->input('search');

    $siswa = \App\Models\Siswa::query()
        ->when($keyword, function ($query, $keyword) {
            $query->where('nama_lengkap', 'like', "%{$keyword}%")
                ->orWhere('nis', 'like', "%{$keyword}%")
                ->orWhere('nisn', 'like', "%{$keyword}%");
        })
        ->orderBy('nama_lengkap')
        ->paginate(10)
        ->withQueryString();

    return view('admin.siswa.index', compact('siswa', 'keyword'));
}

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Siswa::create($validated);

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}
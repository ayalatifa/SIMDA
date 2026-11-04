<?php

namespace App\Http\Controllers;

use App\Models\DokumenAkademik;
use App\Models\Siswa;

class SiswaDokumenController extends Controller
{
    /**
     * Daftar dokumen akademik milik siswa yang sedang login (hanya baca).
     */
    public function index()
    {
        $siswa = Siswa::where('user_id', auth()->id())->firstOrFail();

        $dokumen = DokumenAkademik::where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return view('siswa.dokumen.index', compact('dokumen', 'siswa'));
    }
}

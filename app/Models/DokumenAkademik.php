<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenAkademik extends Model
{
    protected $table = 'dokumen_akademik';

    protected $fillable = [
        'siswa_id',
        'jenis_dokumen',
        'file_dokumen',
        'keterangan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
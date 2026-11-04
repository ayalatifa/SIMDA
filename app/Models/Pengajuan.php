<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    /** Pilihan jenis layanan yang tersedia di form. */
    public const LAYANAN = ['Peminjaman Rapor', 'Legalisir', 'Pengambilan Ijazah', 'Lainnya'];

    /** Lama peminjaman rapor (hari) untuk batas pengembalian otomatis. */
    public const LAMA_PINJAM_HARI = 7;

    protected $fillable = [
        'siswa_id',
        'jenis_layanan',
        'detail',
        'catatan',
        'tanggal_pengajuan',
        'status',
        'tanggal_pinjam',
        'batas_pengembalian',
        'tanggal_pengembalian',
        'tanggal_legalisir',
        'tanggal_pengambilan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_pinjam' => 'date',
        'batas_pengembalian' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_legalisir' => 'date',
        'tanggal_pengambilan' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Ubah nama layanan (termasuk data lama seperti "Legalisir Dokumen")
     * menjadi kategori: rapor | legalisir | ijazah | lainnya.
     */
    public static function kategoriDari(?string $jenis): string
    {
        $jenis = mb_strtolower(trim((string) $jenis));

        if (str_contains($jenis, 'rapor')) {
            return 'rapor';
        }
        if (str_starts_with($jenis, 'legalisir')) {
            return 'legalisir';
        }
        if (str_contains($jenis, 'ijazah')) {
            return 'ijazah';
        }

        return 'lainnya';
    }

    public function getKategoriAttribute(): string
    {
        return self::kategoriDari($this->jenis_layanan);
    }

    /** Filter data berdasarkan kategori layanan. */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return match ($kategori) {
            'rapor' => $query->where('jenis_layanan', 'like', '%Rapor%'),
            'legalisir' => $query->where('jenis_layanan', 'like', 'Legalisir%'),
            'ijazah' => $query->where('jenis_layanan', 'like', '%Ijazah%'),
            default => $query
                ->where('jenis_layanan', 'not like', '%Rapor%')
                ->where('jenis_layanan', 'not like', 'Legalisir%')
                ->where('jenis_layanan', 'not like', '%Ijazah%'),
        };
    }

    /**
     * Kolom tanggal yang sesuai dengan layanan yang dipilih saat pengajuan dibuat.
     * Tanggal layanan diisi pengaju; batas pengembalian dihitung otomatis.
     */
    public static function tanggalLayananBaru(string $kategori, array $input): array
    {
        $hasil = [
            'tanggal_pinjam' => null,
            'batas_pengembalian' => null,
            'tanggal_legalisir' => null,
            'tanggal_pengambilan' => null,
        ];

        switch ($kategori) {
            case 'rapor':
                $hasil['tanggal_pinjam'] = $input['tanggal_pinjam'] ?? null;
                $hasil['batas_pengembalian'] = self::hitungBatas($hasil['tanggal_pinjam']);
                break;
            case 'legalisir':
                $hasil['tanggal_legalisir'] = $input['tanggal_legalisir'] ?? null;
                break;
            default: // ijazah & lainnya
                $hasil['tanggal_pengambilan'] = $input['tanggal_pengambilan'] ?? null;
        }

        return $hasil;
    }

    public static function hitungBatas($tanggalPinjam): ?string
    {
        if (!$tanggalPinjam) {
            return null;
        }

        return \Carbon\Carbon::parse($tanggalPinjam)->addDays(self::LAMA_PINJAM_HARI)->toDateString();
    }

    /** Daftar [label, tanggal] yang relevan untuk ditampilkan sesuai layanan. */
    public function jadwal(): array
    {
        $fmt = fn ($d) => $d ? $d->format('d/m/Y') : null;
        $baris = [];

        switch ($this->kategori) {
            case 'rapor':
                $baris = [
                    ['Pinjam', $fmt($this->tanggal_pinjam)],
                    ['Batas kembali', $fmt($this->batas_pengembalian)],
                    ['Dikembalikan', $fmt($this->tanggal_pengembalian)],
                ];
                break;
            case 'legalisir':
                $baris = [
                    ['Legalisir', $fmt($this->tanggal_legalisir)],
                    ['Dikembalikan', $fmt($this->tanggal_pengembalian)],
                ];
                break;
            default:
                $baris = [['Pengambilan', $fmt($this->tanggal_pengambilan)]];
        }

        return array_values(array_filter($baris, fn ($b) => $b[1]));
    }
}

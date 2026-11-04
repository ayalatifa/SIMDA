<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->string('jenis_layanan');

            $table->text('detail')->nullable();
            $table->text('catatan')->nullable();

            $table->date('tanggal_pengajuan');

            $table->enum('status', [
                'Pending',
                'Disetujui',
                'Ditolak',
                'Diproses',
                'Selesai',
                'Dipinjam',
                'Dikembalikan'
            ])->default('Pending');

            $table->date('tanggal_pinjam')->nullable();
            $table->date('batas_pengembalian')->nullable();
            $table->date('tanggal_pengembalian')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
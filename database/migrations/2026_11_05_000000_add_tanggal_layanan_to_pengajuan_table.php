<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Khusus layanan Legalisir
            $table->date('tanggal_legalisir')->nullable()->after('tanggal_pengembalian');
            // Dipakai layanan Pengambilan Ijazah dan Dokumen Lainnya
            $table->date('tanggal_pengambilan')->nullable()->after('tanggal_legalisir');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_legalisir', 'tanggal_pengambilan']);
        });
    }
};

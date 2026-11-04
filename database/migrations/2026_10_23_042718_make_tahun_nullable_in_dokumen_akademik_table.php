<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_akademik', function (Blueprint $table) {
            $table->string('tahun', 4)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Kolom tetap nullable agar data lama yang kosong tidak bermasalah.
    }
};
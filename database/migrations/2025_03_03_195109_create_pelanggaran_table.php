<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('nis');
            $table->enum('kategori',['ringan','sedang','berat']);
            $table->string('keterangan');
            $table->string('tindakan');
            $table->string('id_pengurus');
            $table->enum('statuspesan', ['Belum', 'Sudah','Sudah1','Selesai'])->default('Belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};

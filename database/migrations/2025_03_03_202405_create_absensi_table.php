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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nis', 100);
            $table->string('id_guru', 100);
            $table->string('id_mapel', 100);
            $table->string('id_kelas', 100);
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};

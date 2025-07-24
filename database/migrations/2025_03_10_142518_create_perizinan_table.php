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
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id(); 
            $table->string('nis', 20);
          $table->string('id_pengurus');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('statuspesan', ['Kembali', 'izin','terlambat'])->default('izin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinan');
    }
};

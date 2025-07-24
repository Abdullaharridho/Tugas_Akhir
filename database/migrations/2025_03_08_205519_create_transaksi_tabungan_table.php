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
        Schema::create('transaksi_tabungan', function (Blueprint $table) {
            $table->id();
            $table->string('nis'); // Pastikan tipe data sesuai dengan tabel santri
            $table->string('id_pengurus'); // Pastikan tipe data sesuai dengan tabel santri
            $table->enum('jenis', ['tabung', 'ambil']);
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_tabungan');
    }
};

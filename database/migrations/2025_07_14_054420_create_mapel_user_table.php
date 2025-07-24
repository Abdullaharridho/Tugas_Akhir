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
        Schema::create('mapel_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->constrained('users')->onDelete('cascade'); // fix disini
            $table->foreignId('mata_pelajaran_id')->constrained('matapelajaran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel_user');
    }
};

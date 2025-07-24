<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('data_santri', function (Blueprint $table) {
            $table->bigIncrements('nis'); // NIS sebagai primary key dan auto increment
            $table->string('nik')->unique(); // NIK wajib unik
            $table->string('nama');
            $table->string('tgllahir');
            $table->string('jenis_kelamin'); // Laki-laki / Perempuan
            $table->string('alamat');
            $table->string('ortu'); // Nama orang tua
            $table->string('kelas');
            $table->string('kamar');
            $table->string('kontak');
            $table->timestamps();
        });


        // Buat trigger untuk auto increment dari 21214000
        DB::unprepared('
            ALTER TABLE data_santri AUTO_INCREMENT = 21214001;
        ');
    }

    public function down() {
        Schema::dropIfExists('data_santri');
    }
};

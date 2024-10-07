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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->string("nip");
            $table->date("hari_tanggal");
            $table->string("jam_pembelajaran");
            $table->string("kelas");
            $table->string("kehadiran");
            $table->string("uraian_kegiatan");
            $table->string("materi")->nullable();
            $table->string("tujuan_pembelajaran")->nullable();
            $table->string("foto_kegiatan");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};

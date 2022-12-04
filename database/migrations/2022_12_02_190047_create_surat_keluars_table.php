<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id');
            $table->string('nomor')->unique('nomor_surat_keluar_unique');
            $table->date('tanggal');
            $table->string('tujuan');
            $table->string('alamat');
            $table->string('perihal');
            $table->string('tempat')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('doc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluar');
    }
};

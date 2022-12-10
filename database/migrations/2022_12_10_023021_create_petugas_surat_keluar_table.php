<?php

use App\Models\SuratKeluar;
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
        Schema::create('petugas_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_keluar_id');
            $table->foreignId('petugas_id');
            $table->timestamps();
        });

        SuratKeluar::all()->each(function ($surat) {
            if ($surat->petugas) $surat->petugases()->attach($surat->petugas_id);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petugas_surat_keluar');
    }
};

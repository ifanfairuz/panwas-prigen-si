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
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->string('type')->default('');
            $table->foreignId('petugas_id')->default(0);
            $table->date('tanggal_dinas_start')->nullable();
            $table->date('tanggal_dinas_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropColumn(['type', 'petugas_id', 'tanggal_dinas_start', 'tanggal_dinas_end']);
        });
    }
};

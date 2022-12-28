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
        Schema::table('petugas', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat', 500)->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('jk', 1)->nullable();
            $table->string('agama')->nullable();
            $table->string('pangkat')->nullable();
            $table->string('tingkat')->nullable();
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('npwp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'alamat',
                'pendidikan',
                'jk',
                'agama',
                'pangkat',
                'tingkat',
                'nik',
                'nip',
                'npwp',
                'no_hp',
                'email',
            ]);
        });
    }
};

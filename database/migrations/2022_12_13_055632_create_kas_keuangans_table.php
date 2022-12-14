<?php

use App\Models\KasKeuangan;
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
        Schema::create('kas_keuangans', function (Blueprint $table) {
            $table->id();
            $table->integer('saldo');
            $table->timestamps();
        });

        KasKeuangan::create([
            'saldo' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kas_keuangans');
    }
};

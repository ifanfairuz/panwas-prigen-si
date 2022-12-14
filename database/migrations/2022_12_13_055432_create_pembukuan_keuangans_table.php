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
        Schema::create('pembukuan_keuangans', function (Blueprint $table) {
            $table->id();
            $table->string('type', 2);
            $table->string('label');
            $table->integer('amount')->default(0);
            $table->float('qty')->default(1.0);
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('pembukuan_keuangans');
    }
};

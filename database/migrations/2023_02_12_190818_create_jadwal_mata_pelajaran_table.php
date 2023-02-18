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
        Schema::dropIfExists('jadwal_mata_pelajaran');
        Schema::create('jadwal_mata_pelajaran', function (Blueprint $table) {
            $table->foreignId('jadwal_id')->constrained();
            $table->foreignId('mata_pelajaran_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_mata_pelajaran');
    }
};

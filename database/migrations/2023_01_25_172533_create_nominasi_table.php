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
        Schema::dropIfExists('nominasi');
        Schema::create('nominasi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('siswa_id')->unsigned()->nullable();
            $table->bigInteger('kelas_id')->unsigned()->nullable();
            $table->string('no_ujian');
            $table->foreign('siswa_id')->references('id')->on('siswa')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('nominasi');
    }
};

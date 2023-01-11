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
        Schema::dropIfExists('siswa');
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('namalengkap');
            $table->bigInteger('kelas_id')->unsigned()->nullable();
            $table->string('noabsen');
            $table->string('nis');
            $table->foreign('user_id')->references('id')->on('users');
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
        //
    }
};

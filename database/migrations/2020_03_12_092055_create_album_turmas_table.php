<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_turmas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("album_id")->nullable();
            $table->foreign('album_id')->references('id')->on('albuns');
            $table->unsignedInteger("turma_id")->nullable();
            $table->foreign('turma_id')->references('id')->on('turmas');
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
        Schema::dropIfExists('album_turmas');
    }
}

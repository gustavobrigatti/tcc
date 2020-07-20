<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAulaTurmaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aula_turma', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("aula_id");
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->unsignedInteger("turma_id");
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->unsignedInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aula_turma');
    }
}

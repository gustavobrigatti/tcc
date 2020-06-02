<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunosResponsaveisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos_responsaveis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("responsavel_id")->nullable();
            $table->foreign('responsavel_id')->references('id')->on('users');
            $table->unsignedInteger("aluno_id")->nullable();
            $table->foreign('aluno_id')->references('id')->on('users');
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
        Schema::dropIfExists('alunos_responsaveis');
    }
}

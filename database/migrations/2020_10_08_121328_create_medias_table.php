<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("turma_id");
            $table->foreign('turma_id')->references('id')->on('turma_id');
            $table->unsignedInteger("user_id");
            $table->foreign('user_id')->references('id')->on('user_id');
            $table->unsignedInteger("aula_id");
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->decimal('media');
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
        Schema::dropIfExists('medias');
    }
}

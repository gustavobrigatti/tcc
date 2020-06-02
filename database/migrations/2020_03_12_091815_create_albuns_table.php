<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albuns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger("escola_id");
            $table->foreign('escola_id')->references('id')->on('instituicoes');
            $table->string('nome');
            $table->text('descricao');
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
        Schema::dropIfExists('albuns');
    }
}

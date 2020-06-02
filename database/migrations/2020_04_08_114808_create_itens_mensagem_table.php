<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItensMensagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_mensagem', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("mensagem_id");
            $table->foreign('mensagem_id')->references('id')->on('mensagens');
            $table->unsignedInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('favorito')->nullable();
            $table->boolean('arquivado')->nullable();
            $table->timestamp('viewed_at')->nullable();
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
        Schema::dropIfExists('itens_mensagem');
    }
}

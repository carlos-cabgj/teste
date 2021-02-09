<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_solicitacao', function (Blueprint $table) {
            $table->id();

            $table->string('no_titulo', 100)->notNull()
                  ->comment('Responsável pelo título da solicitação');

            $table->string('ds_conteudo')->notNull()
                  ->comment('Responsável pelo conteúdo da solicitação');

            $table->softDeletes();

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
        Schema::dropIfExists('s_solicitacao');
    }
}

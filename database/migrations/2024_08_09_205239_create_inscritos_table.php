<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_eventos')->unsigned();
            $table->string('nome', 200);
            $table->string('sobrenome', 200);
            $table->string('email', 100);
            $table->string('telefone', 20);
            $table->string('cpf', 11)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('sexo', 2)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('cep', 9)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_eventos')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};

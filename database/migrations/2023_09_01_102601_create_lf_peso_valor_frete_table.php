<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLfPesoValorFreteTable extends Migration
{
    public function up()
    {
        Schema::create('lf_peso_valor_frete', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_regra')->nullable();
            $table->decimal('peso_minimo', 10, 2)->nullable();
            $table->decimal('peso_maximo', 10, 2)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lf_peso_valor_frete');
    }
}

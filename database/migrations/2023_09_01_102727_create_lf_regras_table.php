<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLfRegrasTable extends Migration
{
    public function up()
    {
        Schema::create('lf_regras', function (Blueprint $table) {
            $table->id();
            $table->string('nome_entrega', 255)->nullable();
            $table->char('tipo', 1)->nullable()->comment('1 - pdv | 2 - consumidor');
            $table->decimal('pedido_minimo', 10, 2)->nullable();
            $table->decimal('valor_frete', 10, 2)->nullable();
            $table->time('horario_corte')->nullable();
            $table->time('horario_atendimento')->nullable();
            $table->time('horario_atendimento_fim')->nullable();
            $table->char('status', 1)->nullable()->comment('1 - Ativo | 2 - Inativo');
            $table->integer('qtde_pedido_maxima_frete')->nullable();
            $table->string('tipo_entrega', 1)->nullable()->comment('0 - Entrega Urgente | 1 - Entrega Expressa | 2 - Entrega Flexível');
            $table->integer('prazo_dias')->nullable();
            $table->time('prazo_horas')->nullable();
            $table->char('consider_polygon', 1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lf_regras');
    }
}


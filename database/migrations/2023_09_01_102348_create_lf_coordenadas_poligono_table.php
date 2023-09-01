<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLfCoordenadasPoligonoTable extends Migration
{
    public function up()
    {
        Schema::create('lf_coordenadas_poligono', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_regra')->nullable();
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lf_coordenadas_poligono');
    }
}


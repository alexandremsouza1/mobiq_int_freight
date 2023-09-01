<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTable extends Migration
{
    public function up()
    {
        Schema::create('log', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('method');
            $table->string('url');
            $table->integer('status_code');
            $table->text('user_agent')->nullable();
            $table->string('request_ip')->nullable();
            $table->text('response_content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log');
    }
}

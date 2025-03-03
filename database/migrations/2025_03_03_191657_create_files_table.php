<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id('file_id');
            $table->string('filename', 255);
            $table->string('path', 255);
            $table->integer('size'); // en bytes
            $table->string('file_type', 50);
            $table->unsignedBigInteger('complaint_id');
            $table->timestamps();

            $table->foreign('complaint_id')->references('complaint_id')->on('complaints');
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
}

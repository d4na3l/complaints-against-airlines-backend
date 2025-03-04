<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferentialTables extends Migration
{
    public function up()
    {
        // Tabla: roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 50);
        });

        // Tabla: document_types
        Schema::create('document_types', function (Blueprint $table) {
            $table->id('document_type_id');
            $table->string('document_type_name', 100);
            $table->string('keyword', 10);
        });

        // Tabla: countries
        Schema::create('countries', function (Blueprint $table) {
            $table->id('country_id');
            $table->string('country_name', 100);
        });

        // Tabla: complaint_status
        Schema::create('complaint_status', function (Blueprint $table) {
            $table->id('complaint_status_id');
            $table->string('status_name', 50);
        });

        // Tabla: motives
        Schema::create('motives', function (Blueprint $table) {
            $table->id('motive_id');
            $table->string('motive', 100);
        });

        // Tabla: flight_types
        Schema::create('flight_types', function (Blueprint $table) {
            $table->id('flight_type_id');
            $table->string('flight_type', 50);
        });

        // Tabla: airlines
        Schema::create('airlines', function (Blueprint $table) {
            $table->id('airline_id');
            $table->string('airline_name', 100);
        });

        // Tabla: airports
        Schema::create('airports', function (Blueprint $table) {
            $table->id('airport_id');
            $table->string('airport_name', 100);
        });

        // Tabla: airline_airports (relación many-to-many)
        Schema::create('airline_airports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airline_id');
            $table->unsignedBigInteger('airport_id');
            $table->unique(['airline_id', 'airport_id']);

            $table->foreign('airline_id')->references('airline_id')->on('airlines');
            $table->foreign('airport_id')->references('airport_id')->on('airports');
        });
    }

    public function down()
    {
        Schema::dropIfExists('airline_airports');
        Schema::dropIfExists('airports');
        Schema::dropIfExists('airlines');
        Schema::dropIfExists('flight_types');
        Schema::dropIfExists('motives');
        Schema::dropIfExists('complaint_status');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('roles');
    }
}

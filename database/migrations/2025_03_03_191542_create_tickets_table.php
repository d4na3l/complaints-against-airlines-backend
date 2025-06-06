<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->string('flight_number', 50);
            $table->string('ticket_number', 50);
            $table->date('flight_date');
            $table->unsignedBigInteger('flight_type_id');
            $table->unsignedBigInteger('airline_id');
            $table->unsignedBigInteger('origin_airport_id');
            $table->unsignedBigInteger('destination_airport_id');
            $table->unsignedBigInteger('user_id'); // Añadido para la relación con usuarios
            $table->timestamps();

            $table->foreign('flight_type_id')->references('flight_type_id')->on('flight_types');
            $table->foreign('airline_id')->references('airline_id')->on('airlines');
            $table->foreign('origin_airport_id')->references('airport_id')->on('airports');
            $table->foreign('destination_airport_id')->references('airport_id')->on('airports');
            $table->foreign('user_id')->references('user_id')->on('users'); // Añadida nueva relación
            // Se eliminó la clave foránea 'incident_airport_id'
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

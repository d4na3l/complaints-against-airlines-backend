<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->date('birthdate');
            $table->string('password', 255);
            $table->string('document', 20);
            $table->unsignedBigInteger('document_type_id');
            $table->string('email', 100)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('local_phone', 20)->nullable();
            $table->string('profession', 100)->nullable();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('nationality_id');      // Representa la nacionalidad o país de origen
            $table->unsignedBigInteger('country_origin_id');   // País de procedencia
            $table->text('domicile_address')->nullable();
            $table->text('additional_address')->nullable();
            $table->timestamps();

            $table->foreign('document_type_id')->references('document_type_id')->on('document_types');
            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->foreign('nationality_id')->references('country_id')->on('countries');
            $table->foreign('country_origin_id')->references('country_id')->on('countries');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

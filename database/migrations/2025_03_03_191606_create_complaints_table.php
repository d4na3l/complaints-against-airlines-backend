<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaint_id');
            $table->timestamp('registration_date')->useCurrent();
            $table->date('incident_date');
            $table->text('description');
            $table->unsignedBigInteger('motive_id');
            $table->unsignedBigInteger('complaint_status_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ticket_id');
            $table->text('processing_notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();

            $table->foreign('motive_id')->references('motive_id')->on('motives');
            $table->foreign('complaint_status_id')->references('complaint_status_id')->on('complaint_status');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets');
            $table->foreign('processed_by')->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}

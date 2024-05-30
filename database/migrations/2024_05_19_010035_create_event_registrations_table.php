<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('affiliation');
            $table->string('ticket_type');
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_registrations');
    }
}


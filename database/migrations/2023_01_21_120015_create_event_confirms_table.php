<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_confirms', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('guest_id');
            $table->integer('host_id');
            $table->integer('event_availibilty_id');
            $table->integer('duration');
            $table->string('location_key');
            $table->enum("status",["pending","complete"])->default("pending");

            $table->text('notes')->nullable();

            $table->enum('duration_type',['m','h'])->default("m");
            $table->timestamp('event_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_confirms');
    }
};

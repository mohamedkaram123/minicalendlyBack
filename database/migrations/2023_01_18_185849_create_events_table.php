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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('link',300)->nullable();
            $table->text('disc')->nullable();
            $table->integer('duration')->default(15);
            $table->enum('duration_type',['m','h'])->default("m");
            $table->enum('active',['on','off'])->default("on");

            $table->integer('date_range')->default(60);
            $table->enum('date_range_custom',['inf','cus'])->default("cus");

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
        Schema::dropIfExists('events');
    }
};

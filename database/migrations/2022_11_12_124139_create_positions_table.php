<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->nullable();
            $table->bigInteger('trader_id')->nullable();
            $table->float('entryPrice')->nullable();
            $table->float('markPrice')->nullable();
            $table->float('roe')->nullable();
            $table->string('type')->nullable();
            $table->string('leverage')->nullable();
            $table->string('amount')->nullable();
            $table->string('yellow')->nullable();
            $table->boolean('existe')->nullable();
            $table->string('updateTime')->nullable();
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
        Schema::dropIfExists('positions');
    }
};

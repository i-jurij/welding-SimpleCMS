<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orgworktimesets', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('lehgth_cal')->nullable();
            $table->string('endtime')->nullable();
            $table->string('tz')->nullable();
            $table->unsignedSmallInteger('period')->nullable();
            $table->string('lunch_time')->nullable();
            $table->string('lunch_duration')->nullable();
            $table->string('work_start')->nullable();
            $table->string('work_end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orgworktimesets');
    }
};

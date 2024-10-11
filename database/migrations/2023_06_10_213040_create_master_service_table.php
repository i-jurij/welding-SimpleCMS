<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public $timestamps = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_service', function (Blueprint $table) {
            $table->foreignId('master_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->primary(['master_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_service');
    }
};

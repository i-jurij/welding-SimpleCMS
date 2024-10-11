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
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->string('master_photo', 1500)->nullable();
            $table->string('master_name', 100);
            $table->string('sec_name', 100)->nullable();
            $table->string('master_fam', 100);
            $table->string('master_phone_number', 100);
            $table->string('spec', 100);
            $table->date('data_priema')->nullable();
            $table->date('data_uvoln')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};

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
        Schema::table('service_categories', function (Blueprint $table) {
            $table->foreignId('page_id')->constrained();
            $table->string('image', 1500);
            $table->string('name', 255);
            $table->string('description', 500);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropColumn('page_id');
            $table->dropColumn('image');
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
    }
};

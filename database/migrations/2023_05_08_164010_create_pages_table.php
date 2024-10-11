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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('alias', 100)->unique();
            $table->string('title', 100);
            $table->string('description', 255);
            $table->string('keywords', 500)->default(null);
            $table->string('robots', 100)->default('INDEX, FOLLOW');
            $table->text('content')->default(null);
            $table->char('single_page', 10)->default('yes');
            $table->string('img')->default(null);
            $table->char('publish', 10)->default('yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};

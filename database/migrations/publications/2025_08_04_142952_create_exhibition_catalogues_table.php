<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exhibition_catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('number_of_pages')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('gallery_or_publisher')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibition_catalogues');
    }
};

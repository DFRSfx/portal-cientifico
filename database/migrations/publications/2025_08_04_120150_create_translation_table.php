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
        Schema::create('translation', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('series_title')->nullable();
            $table->string('volume')->nullable();
            $table->integer('number_of_volumes')->nullable();
            $table->string('edition')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->string('publication_status')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_location')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation');
    }
};

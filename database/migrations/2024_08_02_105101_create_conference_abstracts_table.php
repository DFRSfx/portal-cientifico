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
        Schema::create('conference_abstracts', function (Blueprint $table) {
            $table->id();
            $table->string('article_title');
            $table->string('conference_name')->nullable();
            $table->integer('volume')->nullable();
            $table->integer('issue')->nullable();
            $table->integer('page_range_from')->nullable();
            $table->integer('page_range_to')->nullable();
            $table->string('publication_date')->nullable();
            $table->string('conference_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conference_abstracts');
    }
};

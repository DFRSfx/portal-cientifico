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
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('review_title')->nullable();
            $table->string('published_in')->nullable();
            $table->string('review_volume')->nullable();
            $table->string('review_issue')->nullable();
            $table->string('start_page')->nullable();
            $table->string('end_page')->nullable();
            $table->boolean('refereed')->nullable();
            $table->string('publication_status')->nullable();
            $table->string('review_publication')->nullable();
            $table->string('date_of_review_publication')->nullable();
            $table->string('review_publisher')->nullable();
            $table->string('url')->nullable();
            $table->string('book_title')->nullable();
            $table->string('book_volume')->nullable();
            $table->string('book_edition')->nullable();
            $table->boolean('book_refereed')->nullable();
            $table->string('book_publication_year')->nullable();
            $table->string('book_publication_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_reviews');
    }
};

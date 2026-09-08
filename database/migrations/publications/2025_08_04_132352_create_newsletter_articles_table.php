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
        Schema::create('newsletter_articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_title');
            $table->string('newsletter')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('page_range_from')->nullable();
            $table->string('page_range_to')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('publication_location')->nullable();
            $table->string('url')->nullable();
            $table->longText('research_classifications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_articles');
    }
};

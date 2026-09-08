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
        Schema::create('preface_postfaces', function (Blueprint $table) {
            $table->id();
            $table->string('preface_postface_type')->nullable();
            $table->string('preface_postface_title')->nullable();
            $table->string('book_title')->nullable();
            $table->string('book_volume')->nullable();
            $table->string('book_edition')->nullable();
            $table->integer('preface_postface_page_range_from')->nullable();
            $table->integer('preface_postface_page_range_to')->nullable();
            $table->boolean('refereed')->default(false);
            $table->string('publication_status')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('publication_location')->nullable();
            $table->string('book_publisher')->nullable();
            $table->string('authoring_role')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preface_postfaces');
    }
};

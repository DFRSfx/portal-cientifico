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
        Schema::create('edited_books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('volume')->nullable();
            $table->string('edition')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->boolean('refereed')->nullable();
            $table->string('status')->nullable();
            $table->string('publication_year')->nullable();
            $table->string('pub_country')->nullable();
            $table->string('pub_city')->nullable();
            $table->string('publisher')->nullable();
            $table->string('editing_role')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edited_books');
    }
};

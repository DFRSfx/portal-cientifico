<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("manuals", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("output_id")->nullable();
            $table->string("title")->nullable();
            $table->string("series_title")->nullable();
            $table->string("volume")->nullable();
            $table->integer("number_of_volumes")->nullable();
            $table->string("edition")->nullable();
            $table->integer("number_of_pages")->nullable();
            $table->string("publication_status")->nullable();
            $table->integer("publication_year")->nullable();
            $table->string("publication_location")->nullable();
            $table->string("publisher")->nullable();
            $table->string("authoring_role")->nullable();
            $table->text("url")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("manuals");
    }
};

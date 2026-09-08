<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("reports", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("output_id")->nullable();
            $table->string("report_title")->nullable();
            $table->string("volume")->nullable();
            $table->integer("number_of_pages")->nullable();
            $table->string("institution")->nullable();
            $table->date("date_submitted")->nullable();
            $table->string("authoring_role")->nullable();
            $table->string("publication_status")->nullable();
            $table->text("url")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("reports");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("websites", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("output_id")->nullable();
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->date("launch_date")->nullable();
            $table->text("url")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("websites");
    }
};

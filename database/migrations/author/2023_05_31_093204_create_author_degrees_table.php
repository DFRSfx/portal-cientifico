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
        // rever research-classification
        Schema::create('author_degrees', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("degree_type")->nullable();
            $table->string("degree_name")->nullable();
            $table->string("institution_name")->nullable();
            $table->string("degree_major")->nullable();
            $table->string("description")->nullable();
            $table->string("classification")->nullable();
            $table->string("degree_status")->nullable();
            $table->string("thesis_title")->nullable();
            $table->year("start_date_year")->nullable();
            $table->char("start_date_month", 2)->nullable();
            $table->char("start_date_day", 2)->nullable();
            $table->year("end_date_year")->nullable();
            $table->char("end_date_month", 2)->nullable();
            $table->char("end_date_day", 2)->nullable();
            $table->string("research_classification")->nullable();
            $table->bigInteger("author_id")->unsigned();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_degrees');
    }
};

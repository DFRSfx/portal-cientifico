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
        Schema::create('author_employments', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("employment_category")->nullable();
            $table->string("institution_name")->nullable();
            $table->string("position_type")->nullable();
            $table->string("position_title")->nullable();
            $table->string("position_title_group")->nullable();
            $table->year("start_date_year")->nullable();
            $table->char("start_date_month", 2)->nullable();
            $table->char("start_date_day", 2)->nullable();
            $table->year("end_date_year")->nullable();
            $table->char("end_date_month", 2)->nullable();
            $table->char("end_date_day", 2)->nullable();
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
        Schema::dropIfExists('author_employments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conference_posters', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("conference_name")->nullable();
            $table->string("role")->nullable();
            $table->year("conference_year")->nullable();
            $table->char("conference_month", 2)->nullable();
            $table->char("conference_day", 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conference_posters');
    }
};

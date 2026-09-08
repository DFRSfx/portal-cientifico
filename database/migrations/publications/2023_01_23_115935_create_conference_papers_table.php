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
        Schema::create('conference_papers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('conference_name')->nullable();
            $table->year("presentation_year")->nullable();
            $table->char("presentation_month", 2)->nullable();
            $table->char("presentation_day", 2)->nullable();
            $table->year("conference_year")->nullable();
            $table->char("conference_month", 2)->nullable();
            $table->char("conference_day", 2)->nullable();
            $table->string('conf_country')->nullable();
            $table->string('conf_city')->nullable();
            $table->string('proceedings_title')->nullable();
            $table->integer('start_page')->nullable();
            $table->integer('end_page')->nullable();
            $table->string('status')->nullable();
            $table->string('pub_country')->nullable();
            $table->string('pub_city')->nullable();
            $table->string('publisher')->nullable();
            $table->string('role')->nullable();
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
        Schema::dropIfExists('conference_papers');
    }
};

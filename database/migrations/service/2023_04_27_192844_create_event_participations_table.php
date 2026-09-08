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
        Schema::create('event_participations', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text('event_description')->nullable();
            $table->string('event_name')->nullable();
            $table->string('event_type')->nullable();
            $table->year("start_date_year")->nullable();
            $table->char("start_date_month", 2)->nullable();
            $table->char("start_date_day", 2)->nullable();
            $table->year("end_date_year")->nullable();
            $table->char("end_date_month", 2)->nullable();
            $table->char("end_date_day", 2)->nullable();
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
        Schema::dropIfExists('event_participations');
    }
};

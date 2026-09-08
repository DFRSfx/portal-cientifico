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
        Schema::create('event_administrations', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->year("activity_start_year")->nullable();
            
            $table->char("activity_start_month", 2)->nullable();
            
            $table->char("activity_start_day", 2)->nullable();


            $table->year("activity_end_year")->nullable();

            $table->char("activity_end_month", 2)->nullable();
            
            $table->char("activity_end_day", 2)->nullable();
            

            $table->longText("event_description")->nullable();

            $table->string("event_type")->nullable();

            $table->string("administrative_role")->nullable();
            
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
        Schema::dropIfExists('event_administrations');
    }
};

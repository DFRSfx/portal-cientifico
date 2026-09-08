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
        Schema::create('services', function (Blueprint $table)
        {
            $table->bigIncrements("id");

            $table->year("start_year")->nullable();
            
            $table->char("start_month", 2)->nullable();
            
            $table->char("start_day", 2)->nullable();

            $table->year("end_year")->nullable();
            
            $table->char("end_month", 2)->nullable();
           
            $table->char("end_day", 2)->nullable();

            $table->bigInteger("model_id");

            $table->string("service_type_class")->index();

            // Foreign Keys 

            $table->bigInteger("type_id")->unsigned();
            
            $table->foreign("type_id")->references("id")->on("service_types")->onDelete('cascade');

            $table->bigInteger("author_id")->unsigned();

            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

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
        Schema::dropIfExists('services');
    }
};

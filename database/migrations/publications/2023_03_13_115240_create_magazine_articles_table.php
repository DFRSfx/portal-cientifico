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
        Schema::create('magazine_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string("magazine")->nullable();
            $table->string('role')->nullable();
            $table->string('url')->nullable();
            $table->string('issue')->nullable();
            $table->integer('start_page')->nullable();
            $table->integer('end_page')->nullable();
            $table->string('volume')->nullable();
            $table->year("publication_year")->nullable();
            $table->char("publication_month", 2)->nullable();
            $table->char("publication_day", 2)->nullable();
            $table->string('pub_country')->nullable();
            $table->string('pub_city')->nullable();
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
        Schema::dropIfExists('magazine_articles');
    }
};

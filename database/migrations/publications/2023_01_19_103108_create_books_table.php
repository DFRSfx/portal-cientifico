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
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('volume')->nullable();
            $table->string('status')->nullable();
            $table->integer('pages_number')->nullable();
            $table->string('url')->nullable();
            $table->string('pub_country')->nullable();
            $table->string('pub_city')->nullable();
            $table->string('edition')->nullable();
            $table->boolean('refreed')->nullable();
            $table->string('publisher')->nullable();
            $table->string('role')->nullable();
            $table->year("publication_year")->nullable();
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
        Schema::dropIfExists('books');
    }
};

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
        Schema::create('author_outputs', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("author_id")->unsigned();
            $table->bigInteger("output_id")->unsigned();
            $table->bigInteger("citation_id")->unsigned();
            $table->foreign('output_id')->references('id')->on('outputs')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('citation_id')->references('id')->on('author_citation_names')->onDelete('cascade');
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
        Schema::dropIfExists('author_outputs');
    }
};

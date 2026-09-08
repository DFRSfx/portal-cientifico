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
        Schema::create('output_keywords', function (Blueprint $table) {
            $table->bigInteger("keyword_id")->unsigned();
            $table->bigInteger("output_id")->unsigned();
            $table->foreign("keyword_id")->references("id")->on("keywords")->onDelete('cascade');
            $table->foreign("output_id")->references("id")->on("outputs")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('output_keywords');
    }
};

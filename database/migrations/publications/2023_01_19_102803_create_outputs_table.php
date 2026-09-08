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
        Schema::create('outputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->index();
            $table->string("doi")->nullable();
            $table->bigInteger("type_id")->unsigned();
            $table->foreign("type_id")->references("id")->on("output_types")->onDelete('cascade');
            $table->bigInteger("model_id"); 
            $table->year("year")->nullable();
            $table->string("citation_string");
            $table->integer("ciencia_vitae_pub_id");
            $table->string("output_type_class")->index();
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
        Schema::dropIfExists('outputs');
    }
};

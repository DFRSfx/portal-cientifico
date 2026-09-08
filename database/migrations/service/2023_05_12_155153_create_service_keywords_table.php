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
        Schema::create('service_keywords', function (Blueprint $table) {
            $table->bigInteger("keyword_id")->unsigned();
            $table->bigInteger("service_id")->unsigned();
            $table->foreign("keyword_id")->references("id")->on("keywords")->onDelete('cascade');
            $table->foreign("service_id")->references("id")->on("services")->onDelete('cascade');
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
        Schema::dropIfExists('service_keywords');
    }
};

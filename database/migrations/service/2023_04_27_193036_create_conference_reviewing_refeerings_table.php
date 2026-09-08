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
        Schema::create('conference_reviewing_refeerings', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('conference')->nullable();
            $table->string('conference_host')->nullable();
            $table->string('works_reviewed')->nullable();
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
        Schema::dropIfExists('conference_reviewing_refeerings');
    }
};

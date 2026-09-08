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
        Schema::create('journal_reviewing_refeerings', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('journal')->nullable();
            $table->string('press')->nullable();
            $table->string('works_reviewed')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('journal_reviewing_refeerings');
    }
};

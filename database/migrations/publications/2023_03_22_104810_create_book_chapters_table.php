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
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("book_title")->nullable();
            $table->string("book_volume")->nullable();
            $table->string("book_edition")->nullable();
            $table->year("publication_year")->nullable();
            $table->char("publication_month", 2)->nullable();
            $table->char("publication_day", 2)->nullable();
            $table->string('status')->nullable();
            $table->string('publisher')->nullable();
            $table->string('role')->nullable();
            $table->boolean("refreed")->nullable();
            $table->string("url")->nullable();
            $table->integer('chapter_start_page')->nullable();
            $table->integer('chapter_end_page')->nullable();
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
        Schema::dropIfExists('book_chapters');
    }
};

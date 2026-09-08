<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_spoken_languages', function (Blueprint $table) {
            $table->bigInteger("author_id")->unsigned();
            $table->bigInteger("language_id")->unsigned();
            $table->string("speech_level")->nullable();
            $table->string("writing_level")->nullable();
            $table->string("listening_level")->nullable();
            $table->string("peer_review_level")->nullable();
            $table->string("read-level")->nullable();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->timestamps();
        });

        DB::unprepared('ALTER TABLE `author_spoken_languages` ADD PRIMARY KEY (  `author_id` ,  `language_id` )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_spoken_languages');
    }
};

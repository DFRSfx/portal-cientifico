<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_domain_activities', function (Blueprint $table) {
            $table->bigInteger('topic_id')->unsigned();
            $table->bigInteger("author_id")->unsigned();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('domain_activities_topics')->onDelete('cascade');

            $table->timestamps();
        });

        DB::unprepared('ALTER TABLE `author_domain_activities` ADD PRIMARY KEY (  `topic_id` ,  `author_id` )');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('author_domain_activities');
    }//down
    
};

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
        Schema::create('authors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('orcid')->nullable();
            $table->string('id_google_scholar')->nullable();
            $table->string('id_researcher')->nullable();
            $table->string('id_scopus_author')->nullable();
            $table->date('profile_updated_date')->nullable();
            $table->boolean("profile_image_is_public"); // identifies if a user has any public image
            $table->boolean("profile_is_public");
            $table->text("resume")->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete("cascade");
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
        Schema::dropIfExists('authors');
    }
};

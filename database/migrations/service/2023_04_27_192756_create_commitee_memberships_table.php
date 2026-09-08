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
        Schema::create('commitee_memberships', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text("committee_name")->nullable(); //change this one
            $table->string("membership_type")->nullable();

            // instituitions
            // Classifications
            //keywords
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
        Schema::dropIfExists('commitee_memberships');
    }
};

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
        
        // KeyWords e Classification

        // "fundingInstitutions" , "institutions" ?

        // fundingIdentifiers

        //investigators

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string("funding_category")->nullable();
            $table->string("project_title")->nullable();
            $table->longText("project_description")->nullable();
            $table->year("start_date_year")->nullable();
            $table->char("start_date_month", 2)->nullable();
            $table->char("start_date_day", 2)->nullable();
            $table->year("end_date_year")->nullable();
            $table->char("end_date_month", 2)->nullable();
            $table->char("end_date_day", 2)->nullable();
            $table->year("start_participation_year")->nullable();
            $table->char("start_participation_month", 2)->nullable();
            $table->char("start_participation_day", 2)->nullable();
            $table->year("end_participation_year")->nullable();
            $table->char("end_participation_month", 2)->nullable();
            $table->char("end_participation_day", 2)->nullable();
            $table->string("investigation_role")->nullable();
            $table->longText("investigation_role_description")->nullable();
            $table->boolean("funding_renewable")->nullable();
            $table->boolean("competitive")->nullable();
            $table->string("status")->nullable();
            $table->string("total_amount")->nullable();
            $table->string("program_name")->nullable();
            $table->year("year_awarded")->nullable();
            $table->bigInteger("author_id")->unsigned();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');


            /*
            
            "investigationRole"

            "investigationRoleDescription"
            
            $table->integer("totalAmount");
            
            $table->boolean("competitive");
            
            "fundingRenewable"
            
            "fundingIdentifiers"
            
            "investigators"

            */

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
        Schema::dropIfExists('projects');
    }
};

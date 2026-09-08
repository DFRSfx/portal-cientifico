<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->string("supervisor_name")->nullable();

            $table->string("supervisor_role")->nullable();

            $table->string("ciencia_vitae")->nullable();
            
            $table->bigInteger("degree_id")->unsigned();
            
            $table->foreign('degree_id')->references('id')->on('author_degrees')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};

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
        Schema::table('thesis_dissertations', function (Blueprint $table) {
            $table->string('title');
            $table->integer('volumes_number')->nullable();
            $table->longText('institutions_list')->nullable();
            $table->string('degree_type')->nullable();
            $table->string('classification')->nullable();
            $table->date('date')->nullable();
            $table->date('completionDate')->nullable();
            $table->longText('supervisors')->nullable();
            $table->longText('researchClassifications')->nullable();
            $table->longText('keywords')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

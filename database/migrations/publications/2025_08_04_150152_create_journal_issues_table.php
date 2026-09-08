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
        Schema::create('journal_issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_title');
            $table->string('journal')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue_number')->nullable();
            $table->integer('number_of_pages')->nullable();
            $table->boolean('refereed')->default(false);
            $table->string('publication_status')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('publication_location')->nullable();
            $table->string('editing_role')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_issues');
    }
};

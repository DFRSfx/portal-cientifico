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
        Schema::create('encyclopedia_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_title');
            $table->string('encyclopedia_title');
            $table->string('volume')->nullable();
            $table->integer('number_of_volumes')->nullable();
            $table->string('edition')->nullable();
            $table->string('page_range_from')->nullable();
            $table->string('page_range_to')->nullable();
            $table->string('publication_status')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_location')->nullable();
            $table->string('autoring_role')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encyclopedia_entries');
    }
};

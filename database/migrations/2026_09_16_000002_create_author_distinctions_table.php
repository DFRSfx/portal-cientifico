<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('author_distinctions')) {
            Schema::create('author_distinctions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('author_id');
                $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
                $table->string('distinction_type')->nullable();
                $table->string('distinction_name');
                $table->string('effective_year', 4)->nullable();
                $table->string('effective_month', 2)->nullable();
                $table->string('effective_day', 2)->nullable();
                $table->string('institution_name')->nullable();
                $table->string('country')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('author_distinctions');
    }
};

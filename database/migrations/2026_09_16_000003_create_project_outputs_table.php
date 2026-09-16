<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'ciencia_vitae_funding_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('ciencia_vitae_funding_id')->nullable()->index()->after('id');
            });
        }

        if (!Schema::hasTable('project_outputs')) {
            Schema::create('project_outputs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('project_id');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
                $table->unsignedBigInteger('output_id');
                $table->foreign('output_id')->references('id')->on('outputs')->onDelete('cascade');
                $table->unique(['project_id', 'output_id']);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_outputs');
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'ciencia_vitae_funding_id')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('ciencia_vitae_funding_id');
            });
        }
    }
};

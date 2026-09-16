<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'researchgate_profile')) {
                $table->string('researchgate_profile', 255)->nullable()->after('id_scopus_author');
            }
            if (!Schema::hasColumn('authors', 'id_lattes')) {
                $table->string('id_lattes', 255)->nullable()->after('researchgate_profile');
            }
            if (!Schema::hasColumn('authors', 'h_index')) {
                $table->unsignedSmallInteger('h_index')->nullable()->after('id_lattes');
            }
            if (!Schema::hasColumn('authors', 'h_index_source')) {
                $table->string('h_index_source', 100)->nullable()->after('h_index');
            }
            if (!Schema::hasColumn('authors', 'h_index_reported_at')) {
                $table->date('h_index_reported_at')->nullable()->after('h_index_source');
            }
            if (!Schema::hasColumn('authors', 'h_index_is_self_declared')) {
                $table->boolean('h_index_is_self_declared')->nullable()->after('h_index_reported_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $cols = [];
            foreach (['researchgate_profile', 'id_lattes', 'h_index', 'h_index_source', 'h_index_reported_at', 'h_index_is_self_declared'] as $col) {
                if (Schema::hasColumn('authors', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

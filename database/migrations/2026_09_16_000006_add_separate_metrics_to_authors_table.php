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
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'h_index_scholar')) {
                $table->unsignedSmallInteger('h_index_scholar')->nullable()->after('h_index');
            }
            if (!Schema::hasColumn('authors', 'citations_scholar')) {
                $table->unsignedInteger('citations_scholar')->nullable()->after('h_index_scholar');
            }
            if (!Schema::hasColumn('authors', 'h_index_scopus')) {
                $table->unsignedSmallInteger('h_index_scopus')->nullable()->after('citations_scholar');
            }
            if (!Schema::hasColumn('authors', 'citations_scopus')) {
                $table->unsignedInteger('citations_scopus')->nullable()->after('h_index_scopus');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            foreach (['h_index_scholar', 'citations_scholar', 'h_index_scopus', 'citations_scopus'] as $col) {
                if (Schema::hasColumn('authors', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

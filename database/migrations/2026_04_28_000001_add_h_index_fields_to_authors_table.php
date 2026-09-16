<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'h_index')) {
                $table->unsignedSmallInteger('h_index')->nullable()->after('id_scopus_author');
            }
            if (!Schema::hasColumn('authors', 'h_index_source')) {
                $table->string('h_index_source', 100)->nullable();
            }
            if (!Schema::hasColumn('authors', 'h_index_reported_at')) {
                $table->date('h_index_reported_at')->nullable();
            }
            if (!Schema::hasColumn('authors', 'h_index_is_self_declared')) {
                $table->boolean('h_index_is_self_declared')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn([
                'h_index',
                'h_index_source',
                'h_index_reported_at',
                'h_index_is_self_declared',
            ]);
        });
    }
};

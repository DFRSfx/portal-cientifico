<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->unsignedSmallInteger('h_index')->nullable()->after('id_scopus_author');
            $table->string('h_index_source', 100)->nullable()->after('h_index');
            $table->date('h_index_reported_at')->nullable()->after('h_index_source');
            $table->boolean('h_index_is_self_declared')->nullable()->after('h_index_reported_at');
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

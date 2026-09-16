<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            if (!Schema::hasColumn('outputs', 'isbn')) {
                $table->string('isbn')->nullable()->after('doi');
            }
            if (!Schema::hasColumn('outputs', 'issn')) {
                $table->string('issn')->nullable()->after('isbn');
            }
            if (!Schema::hasColumn('outputs', 'handle')) {
                $table->string('handle')->nullable()->after('issn');
            }
            if (!Schema::hasColumn('outputs', 'pmid')) {
                $table->string('pmid')->nullable()->after('handle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            if (Schema::hasColumn('outputs', 'pmid')) {
                $table->dropColumn('pmid');
            }
            if (Schema::hasColumn('outputs', 'handle')) {
                $table->dropColumn('handle');
            }
            if (Schema::hasColumn('outputs', 'issn')) {
                $table->dropColumn('issn');
            }
            if (Schema::hasColumn('outputs', 'isbn')) {
                $table->dropColumn('isbn');
            }
        });
    }
};

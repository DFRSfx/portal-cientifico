<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'id_authenticus')) {
                $table->string('id_authenticus')->nullable()->after('id_scopus_author');
            }
            if (!Schema::hasColumn('authors', 'researchgate_profile')) {
                $table->string('researchgate_profile')->nullable()->after('id_scopus_author');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['id_authenticus', 'researchgate_profile']);
        });
    }
};

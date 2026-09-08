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
        Schema::table('author_emails', function (Blueprint $table) {
            $table->string('use_type')->nullable()->after('email');
        });

        Schema::table('author_phones', function (Blueprint $table) {
            $table->string('use_type')->nullable()->after('type');
        });

        Schema::table('author_addresses', function (Blueprint $table) {
            $table->string('use_type')->nullable()->after('country');
        });

        Schema::table('author_websites', function (Blueprint $table) {
            $table->string('use_type')->nullable()->after('label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author_emails', function (Blueprint $table) {
            $table->dropColumn('use_type');
        });

        Schema::table('author_phones', function (Blueprint $table) {
            $table->dropColumn('use_type');
        });

        Schema::table('author_addresses', function (Blueprint $table) {
            $table->dropColumn('use_type');
        });

        Schema::table('author_websites', function (Blueprint $table) {
            $table->dropColumn('use_type');
        });
    }
};

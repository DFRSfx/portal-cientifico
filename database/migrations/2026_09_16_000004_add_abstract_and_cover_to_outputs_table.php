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
        Schema::table('outputs', function (Blueprint $table) {
            if (!Schema::hasColumn('outputs', 'abstract')) {
                $table->text('abstract')->nullable()->after('title');
            }
            if (!Schema::hasColumn('outputs', 'cover_image_url')) {
                $table->string('cover_image_url', 500)->nullable()->after('citation_string');
            }
            if (!Schema::hasColumn('outputs', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('cover_image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outputs', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('outputs', 'abstract')) {
                $columnsToDrop[] = 'abstract';
            }
            if (Schema::hasColumn('outputs', 'cover_image_url')) {
                $columnsToDrop[] = 'cover_image_url';
            }
            if (Schema::hasColumn('outputs', 'views_count')) {
                $columnsToDrop[] = 'views_count';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

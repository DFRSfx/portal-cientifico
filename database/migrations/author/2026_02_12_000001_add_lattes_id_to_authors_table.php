<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            if (!Schema::hasColumn('authors', 'id_lattes')) {
                $table->string('id_lattes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['id_lattes']);
        });
    }
};

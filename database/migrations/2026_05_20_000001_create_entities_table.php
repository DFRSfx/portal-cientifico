<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();
        $defaults = [
            'ISLA GAIA',
            'CEOS.PP',
            'ISCAP.PP',
            'ISCAC',
            'IPCA',
            'ISPGAYA',
            'ESEC',
            'UA',
            'ESHT.IPP',
            'OTHER',
        ];

        $rows = array_map(function ($name) use ($now) {
            return [
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $defaults);

        DB::table('entities')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'shelter',
            'species',
            'breeds',
            'pets',
            'form_messages',
            'comments',
            'adoptions',
            'likes',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (! Schema::hasColumn($table, 'uuid')) {
                    $t->uuid('uuid')->unique()->after('id');
                }
            });
        }

        foreach ($tables as $table) {
            DB::table($table)
                ->whereNull('uuid')
                ->orderBy('id')
                ->lazyById()
                ->each(function ($row) use ($table) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                });
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'shelter',
            'species',
            'breeds',
            'pets',
            'form_messages',
            'comments',
            'adoptions',
            'likes',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                if (Schema::hasColumn($t->getTable(), 'uuid')) {
                    $t->dropColumn('uuid');
                }
            });
        }
    }
};

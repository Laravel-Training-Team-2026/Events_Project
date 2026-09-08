<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const DEFAULT_CITIES = [
        'Nablus',
        'Ramallah',
        'Jerusalem',
        'Hebron',
    ];

    public function up(): void
    {
        $timestamp = now();

        DB::table('cities')->insertOrIgnore(array_map(
            fn (string $name) => [
                'name' => $name,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            self::DEFAULT_CITIES
        ));
    }

    public function down(): void
    {
        // The default cities are application data and must not be removed by a rollback.
    }
};

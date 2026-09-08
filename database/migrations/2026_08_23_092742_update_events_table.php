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
        Schema::table('events', function (Blueprint $table) {

            // Remove old columns
            $table->dropColumn([
                'date',
                'time',
            ]);

            // Add new columns
            $table->date('start_date')->after('description');
            $table->date('end_date')->after('start_date');

            $table->time('start_time')->after('end_date');
            $table->time('end_time')->after('start_time');

            $table->string('city')->after('location');

            $table->decimal('price', 10, 2)->after('city');

            $table->unsignedInteger('capacity')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {

            // Remove new columns
            $table->dropColumn([
                'start_date',
                'end_date',
                'start_time',
                'end_time',
                'city',
                'price',
                'capacity',
            ]);

            // Restore old columns
            $table->date('date')->after('description');
            $table->time('time')->after('date');
        });
    }
};
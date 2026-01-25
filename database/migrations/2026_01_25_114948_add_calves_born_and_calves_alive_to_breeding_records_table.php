<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('breeding_records', function (Blueprint $table) {
            $table->integer('calves_born')->nullable()->after('actual_calving_date');
            $table->integer('calves_alive')->nullable()->after('calves_born');
        });
    }

    public function down(): void
    {
        Schema::table('breeding_records', function (Blueprint $table) {
            $table->dropColumn(['calves_born', 'calves_alive']);
        });
    }
};
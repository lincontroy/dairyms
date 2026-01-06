<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAfternoonYieldToMilkProductionsTable extends Migration
{
    public function up()
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            // Add afternoon yield column
            $table->decimal('afternoon_yield', 8, 2)->nullable()->after('morning_yield');
            
            // If total_yield is a generated column, update it
            // If it's a regular column, we'll handle it in the model
        });
        
        // If total_yield is a generated column, drop and recreate it
        // Note: You might need to manually drop the column first
        // Schema::table('milk_productions', function (Blueprint $table) {
        //     $table->dropColumn('total_yield');
        // });
        
        // Then recreate it as generated column
        // Schema::table('milk_productions', function (Blueprint $table) {
        //     $table->decimal('total_yield', 8, 2)->storedAs('morning_yield + afternoon_yield + evening_yield');
        // });
    }

    public function down()
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->dropColumn('afternoon_yield');
        });
    }
}
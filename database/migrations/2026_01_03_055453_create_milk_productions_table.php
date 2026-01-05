<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('milk_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animals');
            $table->date('date');
            $table->decimal('morning_yield', 8, 2);
            $table->decimal('evening_yield', 8, 2);
            $table->decimal('total_yield', 8, 2)->virtualAs('morning_yield + evening_yield');
            $table->integer('lactation_number');
            $table->integer('days_in_milk');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['animal_id', 'date']);
        });
    }
};

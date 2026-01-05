<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('breeding_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade');
            $table->date('date_of_service');
            $table->enum('breeding_method', ['Natural', 'AI', 'Synchronization']);
            $table->string('bull_semen_id')->nullable();
            $table->string('technician')->nullable();
            $table->date('pregnancy_diagnosis_date')->nullable();
            $table->boolean('pregnancy_result')->nullable();
            $table->date('expected_calving_date')->nullable();
            $table->date('actual_calving_date')->nullable();
            $table->string('calving_outcome')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('breeding_records');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('diagnosis');
            $table->text('clinical_signs')->nullable();
            $table->text('treatment');
            $table->string('drug_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('route')->nullable();
            $table->string('duration')->nullable();
            $table->integer('milk_withdrawal_days')->nullable();
            $table->integer('meat_withdrawal_days')->nullable();
            $table->string('veterinarian')->nullable();
            $table->enum('outcome', ['Recovered', 'Under Treatment', 'Not Responding', 'Died']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_records');
    }
};
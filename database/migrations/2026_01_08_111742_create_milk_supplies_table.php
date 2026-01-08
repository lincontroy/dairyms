<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilkSuppliesTable extends Migration
{
    public function up()
    {
        Schema::create('milk_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('quantity_liters', 10, 2);
            $table->decimal('rate_per_liter', 10, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('waste_liters', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('supplied_by')->nullable()->constrained('users');
            $table->foreignId('recorded_by')->constrained('users');
            $table->enum('status', ['pending', 'recorded', 'approved', 'cancelled'])->default('recorded');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index('date');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('milk_supplies');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->string('category');
            $table->text('description');
            $table->string('payment_method')->default('cash');
            $table->string('reference_number')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['date', 'category']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
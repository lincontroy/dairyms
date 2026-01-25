<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calves', function (Blueprint $table) {
            $table->id();
            $table->string('calf_id')->unique(); // Custom ID like CALF-001
            $table->foreignId('dam_id')->constrained('animals')->onDelete('cascade');
            $table->foreignId('sire_id')->nullable()->constrained('animals')->onDelete('set null');
            $table->foreignId('breeding_record_id')->nullable()->constrained('breeding_records')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('ear_tag')->unique();
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_birth');
            $table->date('date_recorded')->default(now());
            $table->decimal('birth_weight', 8, 2)->nullable(); // in kg
            $table->enum('birth_type', ['single', 'twin', 'triplet'])->default('single');
            $table->enum('delivery_type', ['normal', 'assisted', 'caesarean'])->default('normal');
            $table->enum('health_status', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->enum('status', ['alive', 'dead', 'sold', 'transferred'])->default('alive');
            $table->text('notes')->nullable();
            $table->string('color_markings')->nullable();
            $table->string('vaccination_status')->default('pending'); // pending, partial, complete
            $table->date('weaning_date')->nullable();
            $table->decimal('weaning_weight', 8, 2)->nullable();
            $table->boolean('is_weaned')->default(false);
            $table->boolean('requires_special_care')->default(false);
            $table->text('special_care_notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('calf_id');
            $table->index('dam_id');
            $table->index('sire_id');
            $table->index('date_of_birth');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calves');
    }
};
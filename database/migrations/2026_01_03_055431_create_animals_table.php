<?php
// database/migrations/xxxx_create_animals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('animal_id')->unique();
            $table->string('name')->nullable();
            $table->string('ear_tag')->unique();
            $table->string('breed');
            $table->date('date_of_birth');
            $table->enum('sex', ['Male', 'Female']);
            $table->foreignId('dam_id')->nullable()->constrained('animals');
            $table->foreignId('sire_id')->nullable()->constrained('animals');
            $table->enum('source', ['born', 'purchased']);
            $table->enum('status', ['calf', 'heifer', 'lactating', 'dry', 'sold', 'dead'])->default('calf');
            $table->date('date_added');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
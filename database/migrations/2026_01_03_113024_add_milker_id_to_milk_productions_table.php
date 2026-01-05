<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMilkerIdToMilkProductionsTable extends Migration
{
    public function up()
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->foreignId('milker_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->after('animal_id');
        });
    }

    public function down()
    {
        Schema::table('milk_productions', function (Blueprint $table) {
            $table->dropForeign(['milker_id']);
            $table->dropColumn('milker_id');
        });
    }
}
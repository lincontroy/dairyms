<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
};
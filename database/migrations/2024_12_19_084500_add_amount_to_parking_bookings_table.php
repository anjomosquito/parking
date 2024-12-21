<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('parking_bookings', 'amount')) {
            Schema::table('parking_bookings', function (Blueprint $table) {
                $table->decimal('amount', 10, 2)->nullable()->after('car_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('parking_bookings', 'amount')) {
            Schema::table('parking_bookings', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }
};

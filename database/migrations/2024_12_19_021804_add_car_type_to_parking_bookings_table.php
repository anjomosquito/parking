<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parking_bookings', function (Blueprint $table) {
            $table->string('car_type')->nullable()->after('parking_slot_id');
        });
    }

    public function down()
    {
        Schema::table('parking_bookings', function (Blueprint $table) {
            $table->dropColumn('car_type');
        });
    }
};
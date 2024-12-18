<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parking_bookings', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->default(0)->after('notes');
        });
    }

    public function down()
    {
        Schema::table('parking_bookings', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};

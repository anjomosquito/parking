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
    Schema::table('parking_bookings', function (Blueprint $table) {
        // Drop the column if it exists
        if (Schema::hasColumn('parking_bookings', 'amount')) {
            $table->dropColumn('amount');
        }
        
        // Add the column
        $table->decimal('amount', 10, 2)->nullable()->after('car_type');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_bookings', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};

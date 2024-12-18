<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parking_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_spaces')->default(0);
            $table->integer('occupied_spaces')->default(0);
            $table->integer('total_bookings_today')->default(0);
            $table->integer('active_users')->default(0);
            $table->decimal('daily_revenue', 10, 2)->default(0);
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parking_statistics');
    }
};

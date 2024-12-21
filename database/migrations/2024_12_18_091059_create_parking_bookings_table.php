<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parking_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parking_slot_id')->constrained('parking_slots')->onDelete('restrict');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add index for common queries
            $table->index(['status', 'start_time', 'end_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('parking_bookings');
    }
};

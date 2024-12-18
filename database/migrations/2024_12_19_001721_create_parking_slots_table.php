<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_number')->unique(); // Will store values like 'A1', 'A2', etc.
            $table->boolean('is_occupied')->default(false);
            $table->integer('current_number')->default(1);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // Insert initial slot with counter
        DB::table('parking_slots')->insert([
            'slot_number' => 'A1',
            'is_occupied' => false,
            'current_number' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('parking_slots');
    }
};

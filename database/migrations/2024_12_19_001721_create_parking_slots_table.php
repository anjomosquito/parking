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
            $table->string('slot_number')->unique();
            $table->boolean('is_occupied')->default(false);
            $table->timestamps();
        });

        // Insert initial slots A1 to A50
        
    }

    public function down()
    {
        Schema::dropIfExists('parking_slots');
    }
};

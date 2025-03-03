<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dht22_sensor', function (Blueprint $table) {
            $table->id();
            $table->date('reading_date');         
            $table->time('reading_time'); 
            $table->decimal('temperature c', 5, 2);
            $table->decimal('temperature f', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dht22_sensor');
    }
};

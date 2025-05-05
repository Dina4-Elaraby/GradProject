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
    {Schema::create('measurements', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('device_id');
        $table->float('water_level');
        $table->float('dht_humidity');
        $table->float('dht_temperature');
        $table->string('is_moist');
        $table->timestamps();
    
        $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
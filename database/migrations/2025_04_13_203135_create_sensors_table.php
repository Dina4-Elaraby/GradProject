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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->datetime('DHT_Reading'); 
            $table->decimal('DHT_Temperature_C', 5, 2);
            $table->decimal('DHT_Temperature_F', 5, 2);
            $table->decimal('DHT_Humidity', 5, 2);
            $table->string('image_base64');
            $table->boolean('is_moist');
            $table->enum('WaterLevelStatus', ['low', 'medium', 'high']); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};

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
        Schema::create('my_plants', function (Blueprint $table) {
            //Attributes of the table
            $table->id();
            $table->integer('user_id');
            $table->integer('plant_id');
            $table->integer('image_id');

          //Relationships of FK
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade'); 
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_plants');
    }
};

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
        Schema::create('plant_diseases', function (Blueprint $table) {
           $table->integer('plant_id');
            $table->integer('disease_id');

            $table->primary(['plant_id', 'disease_id']);

            $table->foreign('plant_id')->references('id')->on('plants')->onDelete('cascade');
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_diseases');
    }
};

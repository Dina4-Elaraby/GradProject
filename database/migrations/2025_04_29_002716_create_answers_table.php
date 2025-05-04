<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->boolean('is_verified')->default(false);   
            //$table->foreignId('anwser_id')->nullable()->constrained('answers')->onDelete('cascade');
            //cascade delete if the parent answer is deleted   
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

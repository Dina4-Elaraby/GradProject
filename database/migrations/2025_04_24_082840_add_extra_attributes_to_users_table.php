<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('address')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('address');
            $table->string('phone')->nullable()->after('birth_date');        
            $table->string('profile_picture')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('birth_date');
            $table->dropColumn('phone');
            $table->dropColumn('profile_picture');
        });
    }
};

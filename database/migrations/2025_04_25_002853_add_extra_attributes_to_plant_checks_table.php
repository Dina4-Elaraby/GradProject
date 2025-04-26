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
        Schema::table('plant_checks', function (Blueprint $table) {
            $table->string('plant_type')->nullable()->after('result');
            $table->string('diagnosis')->nullable()->after('plant_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plant_checks', function (Blueprint $table) {
            $table->dropColumn('plant_type');
            $table->dropColumn('diagnosis');
        });
    }
};

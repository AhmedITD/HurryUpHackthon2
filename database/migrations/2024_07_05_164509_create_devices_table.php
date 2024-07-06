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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip');
            $table->string('location');
            $table->string('tempC')->default('0');
            $table->string('humi')->default('0');
            $table->string('dsm_consentrate')->default('0');
            $table->string('dsm_particle')->default('0');
            $table->string('air_quality_label')->default('none');
            $table->string('sensor_value')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

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
        Schema::create('historical_reads', function (Blueprint $table) {
            $table->string('device_id');
            $table->string('tempC')->default('0');
            $table->string('humi')->default('0');
            $table->string('dsm_consentrate')->default('0');
            $table->string('dsm_particle')->default('0');
            $table->string('air_quality_label')->default('none');
            $table->string('sensor_value')->default('0');
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('gcodes', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['device_id']);
        });
        
        Schema::dropIfExists('historical_reads');

    }
};

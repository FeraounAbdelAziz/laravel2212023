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
        Schema::create('temperature', function (Blueprint $table) {
            $table->id('idTemperature');
            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('dateCreate')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature');
    }
};
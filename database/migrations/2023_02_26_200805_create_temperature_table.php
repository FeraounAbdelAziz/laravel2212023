<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temperature', function (Blueprint $table) {
            $table->id('idTemperature');
            $table->float('tempValue');
            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('dateCreate')->useCurrent();

        });
        DB::unprepared('
        CREATE TRIGGER temperature_notification
        AFTER INSERT ON temperature
        FOR EACH ROW
        BEGIN
        IF NEW.tempValue > 42 OR NEW.tempValue < 25 THEN
            INSERT INTO notification (content, type, idPatient)
            SELECT CONCAT("Patient ", t.idPatient, " has a temperature out of range: ", t.tempValue," call : " , p.telNum, " "),
                   "Temperature",
                   t.idPatient
            FROM temperature t , person p , patient
            WHERE t.idTemperature = NEW.idTemperature AND patient.idPatient = t.idPatient AND p.idPerson = patient.idPerson;
        END IF;
    END
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temperature');
        DB::unprepared('DROP TRIGGER IF EXISTS temperature_notification');
    }
};

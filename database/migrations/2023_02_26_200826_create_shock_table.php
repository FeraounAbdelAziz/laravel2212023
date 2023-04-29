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
        Schema::create('shock', function (Blueprint $table) {
            $table->id('idShock');
            $table->boolean('shockValue')->default(false);
            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('dateCreate')->useCurrent();
        });
        DB::unprepared('

        CREATE TRIGGER shock_notification
        AFTER INSERT ON shock
        FOR EACH ROW
        BEGIN
            IF NEW.shockValue = 1 THEN
                INSERT INTO notification (content, type, idPatient)
                SELECT CONCAT("Patient ", s.idPatient, " is in shock", " call : " , p.telNum),
                       "Shock",
                       s.idPatient
                FROM shock s , person p , patient
                WHERE s.idShock = NEW.idShock AND patient.idPatient = s.idPatient AND p.idPerson = patient.idPerson;
            END IF;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shock');
        DB::unprepared('DROP TRIGGER IF EXISTS shock_notification');
    }
};

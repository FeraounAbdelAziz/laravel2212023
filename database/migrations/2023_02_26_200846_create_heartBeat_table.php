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
        Schema::create('heartBeat', function (Blueprint $table) {
            $table->id('idHeartBeat');
            $table->float('hbValue');
            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('dateCreate')->useCurrent();
        });
        DB::unprepared('
        CREATE TRIGGER heart_beat_notification
            AFTER INSERT ON heartBeat
            FOR EACH ROW
            BEGIN
                IF NEW.hbValue > 120 OR NEW.hbValue < 50 THEN
                    INSERT INTO notification (content, type, idPatient)
                    SELECT CONCAT("Patient ", h.idPatient, " has a heart beat out of range: ", h.hbValue," call : " , p.telNum),
                           "Heart Beat",
                           h.idPatient
                    FROM heartBeat h, patient pat, person p
                    WHERE h.idHeartBeat = NEW.idHeartBeat AND pat.idPatient = h.idPatient AND pat.idPerson = p.idPerson;
                END IF;
            END;
');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heartBeat');
        DB::unprepared('DROP TRIGGER IF EXISTS heart_beat_notification');
    }
};

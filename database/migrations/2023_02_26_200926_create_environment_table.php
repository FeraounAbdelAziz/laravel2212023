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
        Schema::create('environment', function (Blueprint $table) {
            $table->id('idEnvironment');
            $table->float('tempEnvValue');
            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('dateCreate')->useCurrent();
        });
        DB::unprepared('
        CREATE TRIGGER environment_notification
        AFTER INSERT ON environment
        FOR EACH ROW
        BEGIN
            IF NEW.tempEnvValue > 50 THEN
                INSERT INTO notification (content, type, idPatient)
                SELECT CONCAT("Patient ", e.idPatient, " is in an environment with temperature out of range: ", e.tempEnvValue," call : " , p.telNum),
                       "Temperature Environment",
                       e.idPatient
                FROM environment e , person p , patient
                WHERE e.idEnvironment = NEW.idEnvironment AND patient.idPatient = e.idPatient AND p.idPerson = patient.idPerson;
            END IF;
        END;
');


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environment');
        DB::unprepared('DROP TRIGGER IF EXISTS environment_notification');
    }
};

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
        Schema::create('patient', function (Blueprint $table) {
            $table->id('idPatient');
            $table->unsignedBigInteger('idPerson');
            $table->foreign('idPerson')->references('idPerson')->on('person')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('idDoctor');
            $table->foreign('idDoctor')->references('idDoctor')->on('doctor');
            $table->boolean('assignmentStatus')->default(false);
        });
        DB::unprepared('CREATE TRIGGER person_from_patient_delete AFTER DELETE ON PATIENT FOR EACH ROW
        BEGIN
            DELETE FROM person WHERE idPerson = OLD.idPerson;
        END;');
         DB::unprepared('CREATE TRIGGER person_from_device_update BEFORE DELETE ON PATIENT FOR EACH ROW
         BEGIN
             UPDATE device
             SET assignmentStatus = 0
             WHERE idDevice = (SELECT idDevice FROM assignment WHERE idPatient = OLD.idPatient);
         END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS person_from_patient_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS person_from_device_update');
        Schema::dropIfExists('patient');
    }
};

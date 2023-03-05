<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->id('idDevice');
            $table->boolean('assignmentStatus')->default(false);
            $table->boolean('isOnline')->default(false);
        });
        DB::unprepared('CREATE TRIGGER patient_from_device_update BEFORE DELETE ON device FOR EACH ROW
         BEGIN
             UPDATE patient
             SET assignmentStatus = 0
             WHERE idPatient = (SELECT idPatient FROM assignment WHERE idDevice = OLD.idDevice);
         END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS patient_from_device_update');

        Schema::dropIfExists('device');
    }
};

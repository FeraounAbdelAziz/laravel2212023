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
        Schema::create('assignment', function (Blueprint $table) {
            $table->id('idAssignment');
            $table->unsignedBigInteger('idDevice');
            $table->foreign('idDevice')->references('idDevice')->on('device')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('idPatient');
            $table->foreign('idPatient')->references('idPatient')->on('patient')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamp('dateAssignment')->useCurrent();
            $table->date('returnDate');
            $table->unique(['idPatient']);
            $table->unique(['idDevice']);
            $table->unique(['idDevice', 'idPatient']);
        });

        DB::unprepared('CREATE TRIGGER update_assignment_status AFTER INSERT ON Assignment FOR EACH ROW
        BEGIN
            UPDATE patient SET assignmentStatus = 1 WHERE idPatient = NEW.idPatient;
            UPDATE device SET assignmentStatus = 1 WHERE idDevice = NEW.idDevice;
        END;');

        DB::unprepared('CREATE TRIGGER delete_assignment_status AFTER DELETE ON Assignment FOR EACH ROW
        BEGIN
            UPDATE patient SET assignmentStatus = 0 WHERE idPatient = OLD.idPatient;
            UPDATE device SET assignmentStatus = 0 WHERE idDevice = OLD.idDevice;
        END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('assignment');
        DB::unprepared('DROP TRIGGER IF EXISTS update_assignment_status');
        DB::unprepared('DROP TRIGGER IF EXISTS delete_assignment_status');
    }
};

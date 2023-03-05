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
        Schema::create('doctor', function (Blueprint $table) {
            $table->id('idDoctor');
            $table->string('email');
            $table->string('password');
            $table->boolean('isVerified')->default(false);
            $table->unsignedBigInteger('idPerson');
            $table->foreign('idPerson')->references('idPerson')->on('person')->onDelete('cascade')->onUpdate('cascade');
            $table->unique('email');
        });
        DB::unprepared('CREATE TRIGGER person_from_doctor_delete AFTER DELETE ON DOCTOR FOR EACH ROW
        BEGIN
            DELETE FROM person WHERE idPerson = OLD.idPerson;
        END;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS person_from_doctor_delete');
        Schema::dropIfExists('doctor');
    }
};

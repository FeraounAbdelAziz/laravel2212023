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
        Schema::create('person', function (Blueprint $table) {
            $table->id('idPerson');
            $table->string('firstName');
            $table->string('lastName');
            $table->date('birthdate');
            $table->string('telNum', 10);
            $table->string('adress');
            $table->string('email');
            $table->timestamp('dateCreate')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // public function up()
    // {
    //     Schema::table('appointments', function (Blueprint $table) {
    //         $table->dropUnique('uq_doctor_datetime');
    //         $table->dropColumn(['appointment_date', 'appointment_time']);
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('appointments', function (Blueprint $table) {
    //         $table->date('appointment_date')->nullable();
    //         $table->time('appointment_time')->nullable();

    //         // Re-adding the constraint assuming these were the involved fields
    //         $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'uq_doctor_datetime');
    //     });
    // }
};
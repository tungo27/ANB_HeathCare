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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])
                ->default('pending');
            $table->text('symptoms')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['doctor_id', 'appointment_date', 'appointment_time'], 'uq_doctor_datetime');

            $table->foreign('patient_id')
                ->references('user_id')->on('patients')
                ->onDelete('restrict');
            $table->foreign('doctor_id')
                ->references('user_id')->on('doctors')
                ->onDelete('restrict');
            $table->foreign('schedule_id')
                ->references('id')->on('schedules')
                ->onDelete('restrict');
            $table->foreign('service_id')
                ->references('id')->on('services')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('room', 50)->nullable();
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30);
            $table->integer('max_patients')->default(10);
            $table->boolean('is_available')->default(true);

            $table->unique(['user_id', 'work_date', 'start_time'], 'uq_doctor_slot');
            $table->unique(['room', 'work_date', 'start_time'], 'uq_room_slot');

            $table->foreign('user_id')
                ->references('user_id')->on('doctors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

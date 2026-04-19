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
            $table->unsignedBigInteger('doctor_id');
            $table->string('room', 50)->nullable();
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('is_available')->default(1); // 1: Free, 2: Booked, 3: Canceled/Expired
            $table->timestamps();

            $table->foreign('doctor_id')
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

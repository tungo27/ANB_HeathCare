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
    $table->string('room', 20)->nullable();
    $table->date('work_date');
    $table->time('start_time');
    $table->time('end_time');
    // ❌ LOẠI BỎ is_available: Trạng thái sẽ được tính bằng cách CHECK tồn tại appointment
    
    $table->foreign('doctor_id')->references('user_id')->on('doctors')->onDelete('cascade');
    // Index giúp query lịch nhanh theo ngày/bác sĩ
    $table->index(['doctor_id', 'work_date']); 
    $table->timestamps();
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

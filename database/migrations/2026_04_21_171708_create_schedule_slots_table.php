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
        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');

            // 🎯 Định danh slot
            $table->unsignedInteger('slot_number'); // 1, 2, 3... trong ca
            $table->time('slot_start_time'); // 08:00:00
            $table->time('slot_end_time');   // 08:30:00

            // 📊 Trạng thái slot
            $table->enum('status', [
                'available',   // ✅ Có thể đặt
                'booked',      // 📋 Đã có appointment
                'blocked',     // 🚫 Bác sĩ block (nghỉ, họp, emergency buffer)
                'maintenance'  // 🔧 Tạm khóa để bảo trì
            ])->default('available');

            // 🔗 Link tới appointment khi đã booked
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');

            // 📝 Ghi chú nội bộ (admin/doctor)
            $table->string('internal_note')->nullable(); // "Buffer cho ca khẩn", "Bác sĩ nghỉ lunch"

            // 🔍 Index tối ưu query
            $table->index(['schedule_id', 'status']);
            $table->unique(['schedule_id', 'slot_number']); // 1 slot number duy nhất trong 1 schedule

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_slots');
    }
};

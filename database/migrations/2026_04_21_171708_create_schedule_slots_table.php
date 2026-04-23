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
            // 🔑 Primary Key
            $table->id();

            // 🔗 Foreign Keys
            $table->foreignId('schedule_id')
                  ->constrained('schedules')
                  ->onDelete('cascade')
                  ->comment('Ca làm việc chứa slot này');

            $table->foreignId('appointment_id')
                  ->nullable()
                  ->constrained('appointments')
                  ->nullOnDelete()
                  ->comment('Appointment được đặt vào slot này (nếu có)');

            $table->foreignId('updated_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Admin/Doctor đã cập nhật slot gần nhất');

            // 🎯 Định danh slot
            $table->unsignedTinyInteger('slot_number')
                  ->comment('Thứ tự slot trong ca: 1, 2, 3...');

            $table->time('slot_start_time')
                  ->comment('Giờ bắt đầu slot');

            $table->time('slot_end_time')
                  ->comment('Giờ kết thúc slot');

            // 📊 Trạng thái slot
            $table->enum('status', [
                'available',    // ✅ Có thể đặt lịch
                'booked',       // 📋 Đã có appointment
                'blocked',      // 🚫 Đã block (nghỉ, họp, buffer)
                'maintenance',  // 🔧 Tạm khóa bảo trì hệ thống
            ])
            ->default('available')
            ->index()  // ✅ Index riêng cho status để query nhanh
            ->comment('Trạng thái hiện tại của slot');

            // 📝 Ghi chú nội bộ
            $table->text('internal_note')
                  ->nullable()
                  ->comment('Ghi chú chỉ admin/doctor xem được');

            // 🗓️ Timestamps
            $table->timestamps();

            // 🔍 Indexes tối ưu query thường dùng
            $table->index(['schedule_id', 'status'], 'idx_schedule_status');
            $table->index(['schedule_id', 'slot_number'], 'idx_schedule_slot_number');
            $table->index(['appointment_id'], 'idx_appointment');
            $table->index(['updated_by'], 'idx_updated_by');

            // ✅ Unique: Một slot_number chỉ xuất hiện 1 lần trong 1 schedule
            $table->unique(['schedule_id', 'slot_number'], 'uk_schedule_slot_number');
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
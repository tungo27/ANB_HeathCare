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
            $table->unsignedBigInteger('patient_id'); // FK to users (bệnh nhân)
            $table->unsignedBigInteger('schedule_id'); // FK to schedules
            // ❌ LOẠI BỎ doctor_id: Lấy từ schedule → appointments → doctors

            // ✅ MỞ RỘNG status: Hỗ trợ đầy đủ luồng tương tác
            $table->enum('status', [
                'pending',
                'confirmed',      // Đã đặt thành công
                'completed',      // Đã khám xong
                'cancelled',      // Bệnh nhân huỷ
                'rejected',       // ⭐ Bác sĩ từ chối suất khám
                'no_show',        // Bệnh nhân không đến
                'rescheduled'     // ⭐ Đã đổi sang lịch khác
            ])->default('confirmed');

            // ✅ Trường hỗ trợ tương tác & audit
            $table->text('symptoms')->nullable();              // Triệu chứng ban đầu
            $table->text('diagnosis_result')->nullable();      // ⭐ Kết quả khám (Bác sĩ điền)
            $table->text('cancellation_reason')->nullable();   // ⭐ Lý do huỷ/từ chối
            $table->timestamp('cancelled_at')->nullable();     // ⭐ Thời điểm huỷ (để tính phí phạt nếu có)
            $table->unsignedBigInteger('rescheduled_from_id')->nullable(); // ⭐ Link đến appointment cũ nếu đổi lịch

            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->timestamps();
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

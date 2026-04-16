<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tạo View để tính toán suất trống chính xác
        // Một suất được coi là "bận" nếu đã Confirmed/Completed 
        // hoặc đang Pending mà vẫn chưa quá expires_at
        DB::statement("
            CREATE OR REPLACE VIEW v_schedule_availability AS
            SELECT 
                s.id AS schedule_id,
                s.doctor_id,
                s.work_date,
                s.max_patients,
                (SELECT COUNT(*) FROM appointments a 
                 WHERE a.schedule_id = s.id 
                 AND (a.status IN ('confirmed', 'completed') 
                      OR (a.status = 'pending' AND a.expires_at > CURRENT_TIMESTAMP))
                ) AS booked_count,
                s.max_patients - (
                    SELECT COUNT(*) FROM appointments a 
                    WHERE a.schedule_id = s.id 
                    AND (a.status IN ('confirmed', 'completed') 
                         OR (a.status = 'pending' AND a.expires_at > CURRENT_TIMESTAMP))
                ) AS remaining_slots
            FROM schedules s
        ");

        // 2. Tạo MySQL Event để tự động hủy các lịch pending quá hạn mỗi phút
        DB::statement("SET GLOBAL event_scheduler = ON;");
        DB::statement("
            CREATE EVENT IF NOT EXISTS evt_cleanup_expired_appointments
            ON SCHEDULE EVERY 1 MINUTE
            DO
                UPDATE appointments 
                SET status = 'cancelled', note = 'Hệ thống tự động hủy do hết thời gian giữ chỗ'
                WHERE status = 'pending' 
                  AND expires_at < CURRENT_TIMESTAMP;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_schedule_availability");
        DB::statement("DROP EVENT IF EXISTS evt_cleanup_expired_appointments");
    }
};

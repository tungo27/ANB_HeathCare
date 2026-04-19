<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tạo View để tính toán suất trống chính xác
        DB::statement("
            CREATE OR REPLACE VIEW v_schedule_availability AS
            SELECT
                s.id AS schedule_id,
                s.doctor_id,
                s.work_date,
                (SELECT COUNT(*) FROM appointments a
                 WHERE a.schedule_id = s.id
                 AND a.status IN ('confirmed', 'completed')
                ) AS booked_count
            FROM schedules s
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_schedule_availability");
        DB::statement("DROP EVENT IF EXISTS evt_cleanup_expired_appointments");
    }
};

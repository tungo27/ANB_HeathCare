<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // ✅ Timestamps cho audit
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('confirmed_at');
            
            // ✅ Link 2 chiều cho follow-up
            $table->unsignedBigInteger('follow_up_appointment_id')
                  ->nullable()
                  ->after('rescheduled_from_id');
            
            // ✅ Ghi chú nội bộ
            $table->text('note')->nullable()->after('diagnosis_result');
            
            // ✅ Audit: ai hủy appointment
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_at');
            
            // ✅ Foreign keys
            $table->foreign('follow_up_appointment_id')
                  ->references('id')
                  ->on('appointments')
                  ->nullOnDelete();
                  
            $table->foreign('cancelled_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['follow_up_appointment_id', 'cancelled_by']);
            $table->dropColumn([
                'confirmed_at',
                'completed_at', 
                'follow_up_appointment_id',
                'note',
                'cancelled_by',
            ]);
        });
    }
};
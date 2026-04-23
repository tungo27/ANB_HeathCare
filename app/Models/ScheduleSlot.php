<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleSlot extends Model
{
    protected $fillable = [
        'schedule_id',
        'slot_start_time',
        'slot_end_time',
        'status',          // Cột này đang bị thiếu dẫn đến lỗi
        'appointment_id',
        'internal_note',
        'updated_by',  // Cột này cũng cần thêm để cập nhật ID cuộc hẹn
    ];
    protected $casts = [
        'slot_number' => 'integer',
        'slot_start_time' => 'datetime:H:i',
        'slot_end_time' => 'datetime:H:i',
        'updated_by' => 'integer',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    // ✅ Helper: Book slot
    public function book(Appointment $appointment): bool
    {
        if ($this->status !== 'available') return false;

        return DB::transaction(function () use ($appointment) {
            $this->update([
                'status' => 'booked',
                'appointment_id' => $appointment->id,
            ]);
            return true;
        });
    }

    // ✅ Helper: Hủy booking
    public function release(): bool
    {
        return $this->update([
            'status' => 'available',
            'appointment_id' => null,
        ]);
    }

    
    // ✅ Helper: Block slot
    public function block(string $note = null): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        $data = ['status' => 'blocked'];
        if ($note) {
            $data['internal_note'] = ($this->internal_note ?? '') . "\n[Block: {$note}]";
        }

        return $this->update($data);
    }

    // ✅ Helper: Unblock slot
    public function unblock(): bool
    {
        if ($this->status !== 'blocked') {
            return false;
        }

        return $this->update(['status' => 'available']);
    }

     /**
     * ✅ Kiểm tra slot có đã qua thời gian hiện tại không
     * So sánh: work_date + slot_end_time < now()
     */
    public function isPast(): bool
    {
        // Ghép ngày từ schedule + giờ kết thúc của slot
        $slotEndTime = Carbon::parse(
            $this->schedule->work_date . ' ' . $this->slot_end_time
        );
        
        return $slotEndTime->isPast();
    }

    /**
     * ✅ Kiểm tra slot có thể chỉnh sửa được không
     * (Chưa qua thời gian + không phải status đặc biệt)
     */
    public function isEditable(): bool
    {
        return !$this->isPast() && $this->status !== 'maintenance';
    }
}

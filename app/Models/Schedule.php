<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'room',
        'work_date',
        'start_time',
        'end_time',
        'is_available',
    ];
    // app/Models/Schedule.php

    protected $casts = [
        'work_date' => 'date', // Tự động convert về đối tượng Carbon
    ];
    /**
     * Mối quan hệ: Một lịch làm việc thuộc về một bác sĩ
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }

    // ✅ Quan hệ mới: Một ca có nhiều slot
    public function slots()
    {
        return $this->hasMany(ScheduleSlot::class)->orderBy('slot_number');
    }

    // ✅ Helper: Lấy slot còn trống
    public function getAvailableSlotsAttribute()
    {
        return $this->slots()->where('status', 'available')->get();
    }

    // Thêm dòng này để báo Laravel đừng tự thêm created_at/updated_at vào câu lệnh SQL
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }
}

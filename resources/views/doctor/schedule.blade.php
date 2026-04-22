<!-- resources/views/doctor/schedule.blade.php -->
@extends('layouts.doctor')

@section('main_content')
@include('components.header-doctor')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📅 Lịch làm việc của tôi</h3>
        <div class="btn-group">
            <button class="btn btn-outline-secondary btn-sm" id="viewWeek">Tuần</button>
            <button class="btn btn-outline-secondary btn-sm active" id="viewMonth">Tháng</button>
        </div>
    </div>
    
    {{-- 🗓️ FullCalendar.js integration --}}
    <div id="calendar"></div>
    
    {{-- 📋 Modal: Chi tiết ca khi click --}}
    <div class="modal fade" id="scheduleDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📋 Chi tiết ca: <span id="modalDate"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        {{-- Loaded via AJAX --}}
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Đang tải thông tin...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🔧 FullCalendar + AJAX --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'vi',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: '/api/doctor/schedules', // API trả về events cho calendar
        eventClick: function(info) {
            // Show modal with schedule details
            const modal = new bootstrap.Modal(document.getElementById('scheduleDetailModal'));
            document.getElementById('modalDate').textContent = info.event.title;
            
            fetch(`/api/doctor/schedules/${info.event.id}/slots`)
                .then(res => res.json())
                .then(data => {
                    // Render slots list in modal
                    let html = `<div class="list-group">`;
                    data.slots.forEach(slot => {
                        const badge = {
                            'available': '<span class="badge bg-success">🟢 Trống</span>',
                            'booked': `<span class="badge bg-primary">🔵 ${slot.patient_name}</span>`,
                            'blocked': '<span class="badge bg-danger">🔴 Block</span>',
                        }[slot.status] || '';
                        
                        html += `
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${slot.time}</strong><br>
                                    <small class="text-muted">${slot.internal_note || ''}</small>
                                </div>
                                ${badge}
                            </div>
                        `;
                    });
                    html += `</div>`;
                    document.getElementById('modalContent').innerHTML = html;
                });
            
            modal.show();
        },
        height: 'auto',
        selectable: false // Doctor không tự tạo schedule
    });
    
    calendar.render();
    
    // View toggle
    document.getElementById('viewWeek').onclick = () => calendar.changeView('timeGridWeek');
    document.getElementById('viewMonth').onclick = () => calendar.changeView('dayGridMonth');
});
</script>
@endsection
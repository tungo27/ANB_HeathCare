@extends('layouts.patient')

@section('content')
    <div class="specialty-section">
        <h2>Chọn chuyên khoa</h2>
        <div class="specialty-grid">

            <a href="#" class="specialty-card active" data-id="1" onclick="selectSpecialty(event, this, 1)">

                <span class="specialty-name">Nội khoa</span>
            </a>

            <a href="#" class="specialty-card" data-id="2" onclick="selectSpecialty(event, this, 2)">

                <span class="specialty-name">Nhi khoa</span>
            </a>

            <a href="#" class="specialty-card" data-id="3" onclick="selectSpecialty(event, this, 3)">

                <span class="specialty-name">Sản khoa</span>
            </a>

            <a href="#" class="specialty-card" data-id="4" onclick="selectSpecialty(event, this, 4)">

                <span class="specialty-name">Da liễu</span>
            </a>

            <a href="#" class="specialty-card" data-id="5" onclick="selectSpecialty(event, this, 5)">

                <span class="specialty-name">Răng Hàm Mặt</span>
            </a>

            <a href="#" class="specialty-card" data-id="6" onclick="selectSpecialty(event, this, 6)">

                <span class="specialty-name">Chấn thương chỉnh hình</span>
            </a>

            <a href="#" class="specialty-card" data-id="7" onclick="selectSpecialty(event, this, 7)">

                <span class="specialty-name">Nhãn khoa</span>
            </a>

        </div>
    </div>

    {{-- Doctor section, ẩn mặc định --}}
    <div id="doctor-section" style="display:none; margin-top:20px;">
        <div class="specialty-section">
            <h2>Chọn bác sĩ</h2>
            <div id="doctor-grid" class="doctor-grid"></div>
        </div>
    </div>

    <script>
        const DOCTORS_URL = "{{ route(Auth::user()->role . '.appointment.doctors') }}";

        function selectSpecialty(event, el, specialtyId) {
            event.preventDefault();

            document.querySelectorAll('.specialty-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');

            loadDoctors(specialtyId);
        }

        function loadDoctors(specialtyId) {
            const section = document.getElementById('doctor-section');
            const grid = document.getElementById('doctor-grid');

            section.style.display = 'block';
            grid.innerHTML = Array(4).fill(0).map(() => `<div class="skeleton-card"></div>`).join('');

            fetch(`${DOCTORS_URL}?specialty_id=${specialtyId}`)
                .then(res => res.json())
                .then(doctors => renderDoctors(doctors))
                .catch(() => {
                    grid.innerHTML = `<div class="empty-state">Có lỗi xảy ra. Vui lòng thử lại.</div>`;
                });
        }

        function renderDoctors(doctors) {
            const grid = document.getElementById('doctor-grid');

            if (doctors.length === 0) {
                grid.innerHTML = `<div class="empty-state">Chưa có bác sĩ nào trong chuyên khoa này.</div>`;
                return;
            }

            grid.innerHTML = doctors.map(doc => {
                const avatar = doc.avatar_url ?
                    `<img class="doctor-avatar" src="${doc.avatar_url}" alt="${doc.full_name}">` :
                    `<div class="doctor-avatar-placeholder">${doc.full_name.charAt(0)}</div>`;

                const fee = doc.consultation_fee ?
                    Number(doc.consultation_fee).toLocaleString('vi-VN') + 'đ' :
                    'Liên hệ';

                const rating = doc.avg_rating ?
                    `<span> ${doc.avg_rating} (${doc.total_ratings} đánh giá)</span>` :
                    '';

                return `
        <div class="doctor-card" onclick="selectDoctor(${doc.id})">
            ${avatar}
            <div>
                <div class="doctor-name">BS. ${doc.full_name}</div>
                <div class="doctor-specialty">${doc.specialty_name}</div>
                <div class="doctor-meta">
                    <span>⏱ ${doc.years_of_experience ?? 0} năm kinh nghiệm</span>
                    <span> ${fee}</span>
                    ${rating}
                </div>
            </div>
        </div>`;
            }).join('');
        }

        function selectDoctor(doctorId) {
            // TODO: bước tiếp theo chọn lịch khám
            console.log('Chọn bác sĩ ID:', doctorId);
        }
    </script>
@endsection

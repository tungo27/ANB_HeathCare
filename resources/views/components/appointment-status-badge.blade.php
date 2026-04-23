@props(['status'])

@php
$variants = [
    'pending' => ['class' => 'bg-warning-subtle text-warning', 'label' => 'Chờ xác nhận'],
    'confirmed' => ['class' => 'bg-info-subtle text-info', 'label' => 'Đã xác nhận'],
    'completed' => ['class' => 'bg-success-subtle text-success', 'label' => 'Đã khám'],
    'cancelled' => ['class' => 'bg-danger-subtle text-danger', 'label' => 'Đã hủy'],
    'rejected' => ['class' => 'bg-danger-subtle text-danger', 'label' => 'Bác sĩ từ chối'],
    'no_show' => ['class' => 'bg-secondary-subtle text-secondary', 'label' => 'Không đến'],
    'rescheduled' => ['class' => 'bg-primary-subtle text-primary', 'label' => 'Đã đổi lịch'],
];
$cfg = $variants[$status] ?? ['class' => 'bg-secondary text-white', 'label' => $status];
@endphp

<span class="badge rounded-pill {{ $cfg['class'] }} px-3 py-2">
    {{ $cfg['label'] }}
</span>
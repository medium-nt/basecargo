@props(['status'])

@php
$badgeClasses = match($status) {
    'pending' => 'badge-warning',
    'awaiting_approval' => 'badge-info',
    'approved' => 'badge-success',
    'rejected' => 'badge-secondary',
    default => 'badge-secondary',
};

$statusNames = [
    'pending' => 'Неразобрано',
    'awaiting_approval' => 'На согласовании',
    'approved' => 'Согласовано',
    'rejected' => 'Отклонено',
];
$statusName = $statusNames[$status] ?? '---';
@endphp

<span class="badge {{ $badgeClasses }}">
    {{ $statusName }}
</span>

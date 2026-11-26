@props(['label', 'value', 'color' => 'brand', 'icon' => '📄', 'isText' => false])
@php
    $bgMap = [
        'brand'  => 'bg-brand-50 text-brand-500 dark:bg-brand-500/15',
        'green'  => 'bg-green-50 text-green-600 dark:bg-green-500/15',
        'orange' => 'bg-orange-50 text-orange-500 dark:bg-orange-500/15',
        'red'    => 'bg-red-50 text-red-500 dark:bg-red-500/15',
        'indigo' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15',
    ];
    $iconBg = $bgMap[$color] ?? $bgMap['brand'];
@endphp
<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
        <span class="flex size-11 items-center justify-center rounded-xl text-xl {{ $iconBg }}">{{ $icon }}</span>
    </div>
    @if ($isText)
        <p class="mt-2 text-base font-bold text-gray-900 dark:text-white truncate">{{ $value }}</p>
    @else
        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
    @endif
</div>

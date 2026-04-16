@props(['label', 'icon' => ''])

<button {{ $attributes->merge(['class' => 'px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm flex items-center shadow-sm hover:border-teal-500 hover:text-teal-600 transition']) }}>
    <span>{{ $label }}</span>
</button>
@php
$map = [
    'running'   => 'bg-green-900 text-green-300 ring-green-700',
    'paused'    => 'bg-yellow-900 text-yellow-300 ring-yellow-700',
    'done'      => 'bg-blue-900 text-blue-300 ring-blue-700',
    'error'     => 'bg-red-900 text-red-300 ring-red-700',
    'cancelled' => 'bg-gray-700 text-gray-400 ring-gray-600',
    'pending'   => 'bg-gray-800 text-gray-400 ring-gray-700',
];
$class = $map[$status] ?? 'bg-gray-700 text-gray-400';
@endphp
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs ring-1 {{ $class }}">
    @if($status === 'running')<span class="inline-block w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>@endif
    {{ $status }}
</span>

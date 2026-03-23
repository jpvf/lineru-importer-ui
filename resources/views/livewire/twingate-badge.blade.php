@if($status)
<div class="flex items-center gap-2 text-xs">
    @if($status['success'])
    <span class="flex items-center gap-1.5 text-green-400">
        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
        Twingate OK
        @if($status['latency_ms']) <span class="text-gray-500">{{ $status['latency_ms'] }}ms</span> @endif
    </span>
    @else
    <span class="flex items-center gap-1.5 text-red-400">
        <span class="w-2 h-2 rounded-full bg-red-500"></span>
        Twingate DOWN
    </span>
    @endif
    <span class="text-gray-600">{{ $status['checked_at'] ? \Carbon\Carbon::parse($status['checked_at'])->diffForHumans() : '' }}</span>
</div>
@endif

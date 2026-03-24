<div class="flex items-center gap-2 text-xs">
    @php
        // 'error' from our HTTP client means daemon is unreachable
        // 'success' key present means daemon responded (even if twingate is down)
        $daemonOk = is_array($status) && array_key_exists('success', $status);
    @endphp

    @if(!$daemonOk)
        <span class="text-gray-600">daemon offline</span>
    @elseif($status['success'])
        <span class="flex items-center gap-1.5 text-green-400">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            Aurora OK
            @if(!empty($status['latency_ms'])) <span class="text-gray-500">{{ $status['latency_ms'] }}ms</span> @endif
        </span>
    @else
        <span class="flex items-center gap-1.5 text-amber-400">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            Aurora unreachable
        </span>
    @endif

    @if($daemonOk && !empty($status['checked_at']))
        <span class="text-gray-600">{{ \Carbon\Carbon::parse($status['checked_at'])->diffForHumans() }}</span>
    @endif
</div>

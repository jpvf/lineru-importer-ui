<div>
    {{-- Twingate warning --}}
    @if($twingate && !$twingate['success'])
    <div class="mb-4 px-4 py-3 bg-red-900/50 border border-red-700 rounded-lg flex items-center gap-2 text-red-300 text-sm">
        ⚠️ <strong>Twingate unreachable</strong> — {{ $twingate['error'] }}
        <span class="ml-auto text-xs text-red-400">{{ $twingate['checked_at'] ? \Carbon\Carbon::parse($twingate['checked_at'])->diffForHumans() : '' }}</span>
    </div>
    @endif

    {{-- No active job --}}
    @if(!$job)
    <div class="flex flex-col items-center justify-center py-16 text-gray-500">
        <div class="text-4xl mb-4">🔄</div>
        <p class="text-lg mb-6">No active sync job</p>
        <button
            wire:click="start"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-colors"
        >Start Sync</button>
        @if($flash)
        <p class="mt-3 text-sm text-red-400">{{ $flash }}</p>
        @endif
    </div>
    @else
    {{-- Job header --}}
    <div class="bg-gray-900 rounded-lg border border-gray-800 p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <span class="text-lg font-semibold">Job #{{ $job['id'] }}</span>
                <x-status-badge :status="$job['status']" />
                @if($job['is_paused'] ?? false)
                <span class="text-xs text-yellow-400">⏸ paused</span>
                @endif
            </div>
            <div class="flex gap-2">
                @if($job['status'] === 'running' && !($job['is_paused'] ?? false))
                <button wire:click="pause" class="px-3 py-1.5 text-sm bg-yellow-700 hover:bg-yellow-600 rounded transition-colors">⏸ Pause</button>
                @endif
                @if($job['status'] === 'paused' || ($job['is_paused'] ?? false))
                <button wire:click="resume" class="px-3 py-1.5 text-sm bg-green-700 hover:bg-green-600 rounded transition-colors">▶ Resume</button>
                @endif
                @if(in_array($job['status'], ['running', 'paused']))
                <button wire:click="cancel" onclick="return confirm('Cancel this job?')" class="px-3 py-1.5 text-sm bg-red-800 hover:bg-red-700 rounded transition-colors">✕ Cancel</button>
                @endif
                @if(in_array($job['status'], ['done', 'error', 'cancelled']))
                <button wire:click="start" class="px-3 py-1.5 text-sm bg-indigo-600 hover:bg-indigo-500 rounded transition-colors">▶ New Sync</button>
                @endif
            </div>
        </div>

        {{-- Overall progress --}}
        <div class="space-y-1">
            <div class="flex justify-between text-sm text-gray-400 mb-1">
                <span>{{ number_format($job['rows_done'] ?? 0) }} / {{ number_format($job['rows_total'] ?? 0) }} rows</span>
                <span>{{ $job['pct'] ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-800 rounded-full h-3">
                <div
                    class="bg-indigo-500 h-3 rounded-full transition-all duration-500"
                    style="width: {{ $job['pct'] ?? 0 }}%"
                ></div>
            </div>
            <div class="flex gap-4 text-xs text-gray-500 mt-1">
                <span>{{ $job['tables_done'] ?? 0 }}/{{ $job['tables_total'] ?? 0 }} tables</span>
                @if($job['started_at'])
                <span>Started {{ \Carbon\Carbon::parse($job['started_at'])->diffForHumans() }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Per-table progress --}}
    <div class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Table</th>
                    <th class="px-4 py-3 text-right">Rows</th>
                    <th class="px-4 py-3 text-left w-48">Progress</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($tables as $t)
                @php
                    $pct = $t['rows_total'] > 0 ? round($t['rows_synced'] / $t['rows_total'] * 100) : 0;
                    $barColor = match($t['status']) {
                        'done'    => 'bg-green-500',
                        'error'   => 'bg-red-500',
                        'running' => 'bg-indigo-500',
                        default   => 'bg-gray-600',
                    };
                @endphp
                <tr class="{{ $t['status'] === 'running' ? 'bg-indigo-950/20' : '' }}">
                    <td class="px-4 py-2 font-mono text-white">{{ $t['table_name'] }}</td>
                    <td class="px-4 py-2 text-right text-gray-400 text-xs">
                        {{ number_format($t['rows_synced']) }}/{{ number_format($t['rows_total']) }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-800 rounded-full h-2">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-8 text-right">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <x-status-badge :status="$t['status']" />
                        @if($t['error_message'])
                        <p class="text-xs text-red-400 mt-0.5">{{ $t['error_message'] }}</p>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No tables in this job yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>

<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Sync History</h2>
        <button wire:click="loadJobs" class="text-sm text-gray-400 hover:text-white transition-colors">↻ Refresh</button>
    </div>

    <div class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left">Job</th>
                    <th class="px-4 py-3 text-left">Started</th>
                    <th class="px-4 py-3 text-right">Tables</th>
                    <th class="px-4 py-3 text-right">Rows</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-800/30">
                    <td class="px-4 py-3 text-gray-300">#{{ $job['id'] }}</td>
                    <td class="px-4 py-3 text-gray-400 text-xs">
                        {{ $job['started_at'] ? \Carbon\Carbon::parse($job['started_at'])->format('M d H:i') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right text-gray-300">{{ $job['tables_done'] }}/{{ $job['tables_total'] }}</td>
                    <td class="px-4 py-3 text-right text-gray-300">{{ number_format($job['rows_done']) }}</td>
                    <td class="px-4 py-3 text-center"><x-status-badge :status="$job['status']" /></td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="toggleExpand({{ $job['id'] }})" class="text-xs text-indigo-400 hover:text-indigo-300">
                            {{ $expandedId === $job['id'] ? 'Hide' : 'Details' }}
                        </button>
                    </td>
                </tr>
                @if($expandedId === $job['id'] && $jobDetail)
                <tr class="bg-gray-950">
                    <td colspan="6" class="px-6 py-3">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="text-gray-500">
                                    <th class="text-left py-1">Table</th>
                                    <th class="text-right py-1">Rows</th>
                                    <th class="text-center py-1">Status</th>
                                    <th class="text-left py-1">Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobDetail as $t)
                                <tr class="border-t border-gray-800">
                                    <td class="font-mono py-1 text-gray-300">{{ $t['table_name'] }}</td>
                                    <td class="text-right py-1 text-gray-400">{{ number_format($t['rows_synced']) }}</td>
                                    <td class="text-center py-1"><x-status-badge :status="$t['status']" /></td>
                                    <td class="py-1 text-red-400">{{ $t['error_message'] ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No jobs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

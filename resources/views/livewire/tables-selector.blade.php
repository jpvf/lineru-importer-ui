<div>
    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-3 mb-4">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Search tables..."
            class="bg-gray-800 border border-gray-700 rounded px-3 py-2 text-sm text-white placeholder-gray-500 w-64 focus:outline-none focus:border-indigo-500"
        />

        <select
            wire:model.live="filterStrategy"
            class="bg-gray-800 border border-gray-700 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500"
        >
            <option value="">All strategies</option>
            <option value="auto_increment">Auto increment</option>
            <option value="datetime">Datetime</option>
            <option value="full">Full (no incremental)</option>
        </select>

        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
            <input type="checkbox" wire:model.live="onlySelected" class="rounded">
            Selected only
        </label>

        <div class="flex gap-2 ml-auto">
            <button wire:click="selectAll" class="px-3 py-2 text-sm bg-gray-700 hover:bg-gray-600 rounded transition-colors">Select all visible</button>
            <button wire:click="deselectAll" class="px-3 py-2 text-sm bg-gray-700 hover:bg-gray-600 rounded transition-colors">Deselect all</button>
            <button
                wire:click="discover"
                wire:loading.attr="disabled"
                class="px-3 py-2 text-sm bg-indigo-600 hover:bg-indigo-500 rounded transition-colors disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="discover">🔍 Discover tables</span>
                <span wire:loading wire:target="discover">Discovering...</span>
            </button>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="flex gap-6 mb-4 text-sm text-gray-400">
        <span>{{ count($filteredTables) }} tables shown</span>
        <span>Selected: <strong class="text-white">@bytes($selectedBytes)</strong></span>
        <span>Total DB: <strong class="text-gray-300">@bytes($totalBytes)</strong></span>
    </div>

    @if($flash)
    <div class="mb-4 px-4 py-2 bg-indigo-900/50 border border-indigo-700 rounded text-sm text-indigo-300">
        {{ $flash }}
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800 text-gray-400 text-xs uppercase tracking-wide">
                    <th class="px-4 py-3 text-left w-10">Sync</th>
                    <th class="px-4 py-3 text-left">Table</th>
                    <th class="px-4 py-3 text-right">Est. Rows</th>
                    <th class="px-4 py-3 text-right">Size</th>
                    <th class="px-4 py-3 text-center">Strategy</th>
                    <th class="px-4 py-3 text-center">Generated cols</th>
                    <th class="px-4 py-3 text-center">Last sync</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($filteredTables as $t)
                <tr class="hover:bg-gray-800/50 transition-colors {{ $t['sync_data'] ? 'bg-indigo-950/20' : '' }}">
                    <td class="px-4 py-2 text-center">
                        <input
                            type="checkbox"
                            {{ $t['sync_data'] ? 'checked' : '' }}
                            wire:click="toggleTable('{{ $t['schema_name'] }}', '{{ $t['table_name'] }}', {{ $t['sync_data'] ? 'false' : 'true' }})"
                            class="rounded accent-indigo-500 cursor-pointer"
                        />
                    </td>
                    <td class="px-4 py-2 font-mono text-white">{{ $t['table_name'] }}</td>
                    <td class="px-4 py-2 text-right text-gray-400">{{ number_format($t['row_count_estimate']) }}</td>
                    <td class="px-4 py-2 text-right font-medium {{ $t['data_length_bytes'] > 1e9 ? 'text-amber-400' : 'text-gray-300' }}">
                        @bytes($t['data_length_bytes'])
                    </td>
                    <td class="px-4 py-2 text-center">
                        @php $badges = ['auto_increment' => 'bg-green-900 text-green-300', 'datetime' => 'bg-blue-900 text-blue-300', 'full' => 'bg-gray-700 text-gray-400'] @endphp
                        <span class="px-2 py-0.5 rounded text-xs {{ $badges[$t['cursor_strategy']] ?? 'bg-gray-700 text-gray-400' }}">
                            {{ $t['cursor_strategy'] }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center text-gray-500 text-xs">
                        {{ $t['has_generated_cols'] ? implode(', ', json_decode($t['generated_col_names'] ?? '[]')) : '—' }}
                    </td>
                    <td class="px-4 py-2 text-center text-gray-500 text-xs">
                        {{ $t['last_synced_at'] ? \Carbon\Carbon::parse($t['last_synced_at'])->diffForHumans() : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No tables found. Run discovery first.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

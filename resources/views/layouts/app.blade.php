<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Sync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { gray: { 950: '#0a0a0f' } } } }
        }
    </script>
    @livewireStyles
</head>
<body class="h-full text-gray-100" x-data="{ tab: '{{ request()->query('tab', 'tables') }}' }">

<div class="min-h-full flex flex-col">

    {{-- Header --}}
    <header class="bg-gray-900 border-b border-gray-800 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold tracking-tight text-white">⚡ Aurora Sync</span>
        </div>
        <livewire:twingate-status />
    </header>

    {{-- Tab Nav --}}
    <nav class="bg-gray-900 border-b border-gray-800 px-6">
        <div class="flex gap-1">
            @foreach(['tables' => 'Tables', 'progress' => 'Progress', 'history' => 'History', 'settings' => 'Settings'] as $key => $label)
            <button
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}'
                    ? 'border-b-2 border-indigo-500 text-white'
                    : 'text-gray-400 hover:text-gray-200'"
                class="px-4 py-3 text-sm font-medium transition-colors"
            >{{ $label }}</button>
            @endforeach
        </div>
    </nav>

    {{-- Content --}}
    <main class="flex-1 p-6">
        <div x-show="tab === 'tables'">
            <livewire:tables-selector />
        </div>
        <div x-show="tab === 'progress'">
            <livewire:sync-progress />
        </div>
        <div x-show="tab === 'history'">
            <livewire:job-history />
        </div>
        <div x-show="tab === 'settings'">
            <livewire:app-settings />
        </div>
    </main>

</div>

@livewireScripts
</body>
</html>

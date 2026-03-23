<div class="max-w-2xl">
    @if($flash)
    <div class="mb-4 px-4 py-2 bg-green-900/50 border border-green-700 rounded text-sm text-green-300">{{ $flash }}</div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- Aurora --}}
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-5">
            <h3 class="font-semibold text-white mb-4">Aurora (Source — Read Only)</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Host" name="form.aurora_host" />
                <x-form-field label="Port" name="form.aurora_port" type="number" />
                <x-form-field label="User" name="form.aurora_user" />
                <x-form-field label="Password" name="form.aurora_password" type="password" />
                <x-form-field label="Schema / Database" name="form.aurora_schema" class="col-span-2" />
            </div>
            <div class="mt-3 flex items-center gap-3">
                <button type="button" wire:click="testAurora" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 rounded">Test connection</button>
                @if(isset($testResults['aurora']))
                    @php $r = $testResults['aurora']; $ok = $r['ok'] ?? false; @endphp
                    <span class="{{ $ok ? 'text-green-400' : 'text-red-400' }} text-sm">
                        {{ $ok ? '✓ Connected' : '✗ ' . ($r['error'] ?? 'Failed') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Local MySQL --}}
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-5">
            <h3 class="font-semibold text-white mb-4">Local MySQL (Target)</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Host" name="form.local_host" />
                <x-form-field label="Port" name="form.local_port" type="number" />
                <x-form-field label="User" name="form.local_user" />
                <x-form-field label="Password" name="form.local_password" type="password" />
            </div>
            <div class="mt-3 flex items-center gap-3">
                <button type="button" wire:click="testLocal" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 rounded">Test connection</button>
                @if(isset($testResults['local']))
                    @php $r = $testResults['local']; $ok = $r['ok'] ?? false; @endphp
                    <span class="{{ $ok ? 'text-green-400' : 'text-red-400' }} text-sm">
                        {{ $ok ? '✓ Connected' : '✗ ' . ($r['error'] ?? 'Failed') }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Sync options --}}
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-5">
            <h3 class="font-semibold text-white mb-4">Sync Options</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Batch size (rows)" name="form.batch_size" type="number" />
                <x-form-field label="Twingate check interval (sec)" name="form.twingate_check_interval" type="number" />
            </div>
        </div>

        {{-- Telegram --}}
        <div class="bg-gray-900 rounded-lg border border-gray-800 p-5">
            <h3 class="font-semibold text-white mb-4">Telegram Notifications</h3>
            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Bot Token" name="form.telegram_bot_token" type="password" />
                <x-form-field label="Chat ID" name="form.telegram_chat_id" />
            </div>
            <div class="mt-3 flex items-center gap-3">
                <button type="button" wire:click="testTelegram" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 rounded">Send test message</button>
                @if(isset($testResults['telegram']))
                    @php $r = $testResults['telegram']; $ok = $r['ok'] ?? false; @endphp
                    <span class="{{ $ok ? 'text-green-400' : 'text-red-400' }} text-sm">
                        {{ $ok ? '✓ Sent' : '✗ ' . ($r['error'] ?? 'Failed') }}
                    </span>
                @endif
            </div>
        </div>

        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition-colors">
            Save settings
        </button>
    </form>
</div>

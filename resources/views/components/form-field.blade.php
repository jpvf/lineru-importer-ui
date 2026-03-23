@props(['label', 'name', 'type' => 'text', 'class' => ''])

<div class="{{ $class }}">
    <label class="block text-xs text-gray-400 mb-1">{{ $label }}</label>
    <input
        type="{{ $type }}"
        wire:model="{{ $name }}"
        class="w-full bg-gray-800 border border-gray-700 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-indigo-500"
    />
</div>

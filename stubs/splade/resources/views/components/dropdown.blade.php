@props(['width' => '48'])

@php
switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp

<x-splade-dropdown {{ $attributes->except('width') }}>
    <x-slot:trigger>
        {{ $trigger }}
    </x-slot:trigger>

    <div class="mt-2 {{ $width }} rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 bg-white">
        {{ $content }}
    </div>
</x-splade-dropdown>

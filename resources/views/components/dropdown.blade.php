@props(['align' => 'right', 'width' => '48'])

<div class="relative" x-data="{ open: false }">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open" @click.away="open = false"
        class="absolute z-50 mt-2 py-2 bg-white border rounded shadow-lg w-{{ $width }}">
        {{ $content }}
    </div>
</div>

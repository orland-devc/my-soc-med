@php
$iconTrailing = $iconTrailing ??= $attributes->pluck('icon:trailing');
$iconVariant = $iconVariant ??= $attributes->pluck('icon:variant');
@endphp

@aware(['variant'])

@props([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'badgeColor' => null,
    'variant' => null,
    'iconDot' => null,
    'accent' => true,
    'square' => true,
    'badge' => null,
    'icon' => null,
    'image' => null,
])

@php
$iconClasses = Flux::classes('size-6');

$classes = Flux::classes()
    ->add('px-3 flex items-center justify-center relative')
    ->add('text-zinc-400 dark:text-zinc-600 transition-all')
    ->add('data-current:after:absolute data-current:after:bottom-0 data-current:after:inset-x-0 data-current:after:h-[2px] data-current:bg-black/10 dark:data-current:bg-zinc-800')
    ->add([
        '[--hover-fill:color-mix(in_oklab,_var(--color-accent-content),_transparent_90%)]',
    ])
    ->add(match ($accent) {
        true => [
            'hover:text-zinc-800 dark:hover:text-white',
            'data-current:text-(--color-accent-content) hover:data-current:text-(--color-accent-content) hover:bg-zinc-800/5 dark:hover:bg-white/10 hover:data-current:bg-(--hover-fill)',
            'data-current:after:bg-(--color-accent-content)',
        ],
        false => [
            'hover:text-zinc-800 dark:hover:text-white',
            'data-current:text-zinc-800 dark:data-current:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-white/10',
            'data-current:after:bg-zinc-800 dark:data-current:after:bg-white',
        ],
    });
@endphp

<flux:button-or-link :attributes="$attributes->class($classes)" data-flux-navbar-items>
    {{-- Main Icon or Image --}}
    @if ($image)
        <div class="relative">
            <img 
                src="{{ asset($image) }}" 
                alt="Profile" 
                class="w-7 h-7 rounded-full border-2 border-zinc-300 dark:border-zinc-600 object-cover
                       data-current:after:border-black dark:data-current:after:border-white"
            />
        </div>
    @elseif ($icon)
        <div class="relative">
            @if (is_string($icon))
                <x-dynamic-component :component="'lucide-' . $icon" class="{{ $iconClasses }}" />
            @else
                {{ $icon }}
            @endif

            @if ($iconDot)
                <div class="absolute top-[-2px] end-[-2px]">
                    <div class="size-[6px] rounded-full bg-zinc-500 dark:bg-zinc-400"></div>
                </div>
            @endif
        </div>
    @endif

    {{-- Trailing Icon --}}
    @if (is_string($iconTrailing))
        <x-dynamic-component :component="'lucide-' . $iconTrailing" class="w-5 h-5 ms-1" />
    @elseif ($iconTrailing)
        {{ $iconTrailing }}
    @endif

    {{-- Badge --}}
    @if ($badge)
        <flux:navbar.badge :color="$badgeColor" class="ms-2">
            {{ $badge }}
        </flux:navbar.badge>
    @endif
</flux:button-or-link>

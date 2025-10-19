@php $iconTrailing = $iconTrailing ??= $attributes->pluck('icon:trailing'); @endphp
@php $iconVariant = $iconVariant ??= $attributes->pluck('icon:variant'); @endphp

@aware([ 'variant' ])

@props([
    'iconVariant' => 'outline',
    'iconTrailing' => null,
    'badgeColor' => null,
    'variant' => null,
    'iconDot' => null,
    'accent' => true,
    'square' => null,
    'badge' => null,
    'icon' => null,
])

@php
$square ??= $slot->isEmpty();
$iconClasses = Flux::classes($square ? 'size-6' : 'size-5');

$classes = Flux::classes()
    ->add('px-3 flex items-center relative')
    ->add($square ? '' : 'px-2.5!')
    ->add('text-zinc-400 dark:text-zinc-600 transition-all')
    ->add('data-current:after:absolutedata-current:after:inset-x-0 data-current:after:h-[2px]')
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
    {{-- Leading Icon --}}
    @if ($icon)
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

    {{-- Text Slot --}}
    @if ($slot->isNotEmpty())
        <div class="{{ $icon ? 'ms-3' : '' }} flex-1 text-xl font-medium leading-none whitespace-nowrap [[data-nav-footer]_&]:hidden [[data-nav-sidebar]_[data-nav-footer]_&]:block" data-content>
            {{ $slot }}
        </div>
    @endif

    {{-- Trailing Icon --}}
    @if (is_string($iconTrailing))
        <x-dynamic-component :component="'lucide-' . $iconTrailing" class="w-5 h-5" />
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

@props([
    'sticky' => null,
    'container' => null,
])

@php
$classes = Flux::classes('[grid-area:footer]')
    ->add('z-45 min-h-14 px-6 justify-between')
    ->add($container ? '' : 'flex lg:px-8')
    ->add('mt-auto') // Pushes it to bottom if flex-column layout
    ;

if ($sticky) {
    $attributes = $attributes->merge([
        'x-data' => '',
        'x-bind:style' => '{ position: \'sticky\', bottom: 0, \'max-height\': \'calc(100vh - \' + $el.offsetTop + \'px)\' }',
    ]);
}
@endphp

<footer {{ $attributes->class($classes) }} data-flux-footer>
    @if ($container)
        <div class="mx-auto w-full h-full [:where(&)]:max-w-7xl px-6 lg:px-8 flex items-center">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</footer>
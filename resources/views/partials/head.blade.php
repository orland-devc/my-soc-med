<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('images/flexy/flexy-logo.png') }}" sizes="any">
<link rel="icon" href="{{ asset('images/flexy/flexy-logo.png') }}" type="image/svg+xml">
{{-- <link rel="apple-touch-icon" href="/apple-touch-icon.png"> --}}

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Lightbox2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/css/lightbox.min.css" rel="stylesheet" />

<!-- Lightbox2 JS -->
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/js/lightbox-plus-jquery.min.js"></script>


<style>
    * {
        font-family: Poppins;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance


<script>
    lightbox.option({
        resizeDuration: 200,
        wrapAround: true,
        fadeDuration: 200,
        imageFadeDuration: 200,
        alwaysShowNavOnTouchDevices: true
    });
</script>

{{-- resources/views/errors/not-found.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-6xl font-bold mb-4">404</h1>
    <p class="text-lg text-gray-400 mb-6">The page you’re looking for doesn’t exist.</p>
    <a href="{{ route('posts') }}" 
       class="px-6 py-3 bg-blue-600 rounded-lg hover:bg-blue-700 transition">
        Go Back Home
    </a>
</body>
</html>

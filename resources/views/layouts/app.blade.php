<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RSS Handler')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('feeds.index') }}" class="text-lg font-semibold">RSS Handler</a>
            <a href="{{ route('feeds.create') }}" class="text-sm text-blue-600 hover:text-blue-800">+ Add Feed</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-50 border border-green-200 text-green-800 px-4 py-2 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

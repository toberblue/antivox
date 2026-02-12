<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'antivox' }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 antialiased">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('blog.index') }}" class="text-2xl font-bold text-gray-900">
                    Peter Matthews
                </a>
            </div>
            
            <!-- Search -->
            <form action="{{ route('blog.search') }}" method="GET" class="mt-4">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search..." 
                    value="{{ request('q') }}"
                    class="input"
                >
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <p class="text-center text-gray-600 text-sm">
                © {{ date('Y') }} Peter Matthews
            </p>
        </div>
    </footer>
    
    <!-- Power of 8 Widget -->
    <script src="https://powerof8.nz/widget/embed.js" defer></script>
</body>
</html>

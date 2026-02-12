<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Henley Blog' }} - Peter Matthews</title>
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
                <p class="text-gray-600 text-sm hidden md:block">
                    Writer, musician, coder, want to be boatbuilder.
                </p>
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

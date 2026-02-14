<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>antivox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        .banner-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        
        .banner-container img {
            display: block;
            width: auto;
            height: auto;
            min-width: 150%;
            animation: pan 240s linear infinite;
        }
        
        @keyframes pan {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-33.33%); }
        }
        
        .hero-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #002147;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: rotate 30s linear infinite;
        }
        
        @media (min-width: 768px) {
            .hero-circle {
                width: 160px;
                height: 160px;
                border: 3px solid #002147;
            }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">

    <!-- Articles Section -->
    <section id="articles" class="bg-white">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <!-- Header -->
            <div class="flex items-center gap-6 mb-4">
                <img src="/storage/hero/yy.png" alt="Hero" class="hero-circle">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-700">antivox</h1>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="mb-4 flex gap-3">
                <form action="{{ route('blog.search') }}" method="GET" class="flex-1">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search articles..." 
                        value="{{ request('q') }}"
                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                </form>
                
                <form action="{{ route('blog.index') }}" method="GET" style="width: 240px;">
                    <select 
                        name="category" 
                        onchange="this.form.submit()"
                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option 
                                value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">All Articles</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($posts as $post)
                    <article class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                        <a href="{{ route('blog.show', $post) }}" class="flex gap-3 p-3 items-start">
                            @if($post->featured_image)
                                <div class="flex-shrink-0 w-20 h-20">
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover rounded">
                                </div>
                            @else
                                <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-primary-600 mb-1 line-clamp-2">
                                    {{ $post->title }}
                                </h3>

                                <div class="text-xs text-gray-500 mb-1">
                                    @if($post->category)
                                        <span class="text-primary-600">{{ $post->category->name }}</span>
                                        <span class="mx-1">•</span>
                                    @endif
                                    {{ $post->published_at->format('Y-m-d') }}
                                </div>

                                @if($post->sub_heading)
                                    <p class="text-gray-600 text-xs line-clamp-2">{{ $post->sub_heading }}</p>
                                @endif
                            </div>
                        </a>
                    </article>
                @empty
                    <p class="text-gray-600 col-span-2">No posts found.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white border-t" style="background-color: #000000;">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <p class="text-center text-gray-300 text-sm">
                © {{ date('Y') }} antivox
            </p>
        </div>
    </footer>
    
    <!-- Power of 8 Widget -->
    <script src="https://powerof8.nz/widget/embed.js" data-size="small" defer></script>
</body>
</html>

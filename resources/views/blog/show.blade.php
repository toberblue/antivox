@extends('layouts.app')

@section('content')
<article>
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('blog.index') }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to all posts
        </a>

        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

        <div class="flex items-center text-sm text-gray-500">
            @if($post->category)
                <a href="{{ route('blog.category', $post->category->slug) }}" class="text-primary-600 hover:underline">
                    {{ $post->category->name }}
                </a>
                <span class="mx-2">•</span>
            @endif
            <span>{{ $post->author }}</span>
            <span class="mx-2">•</span>
            <span>{{ $post->published_at->format('F j, Y') }}</span>
        </div>
    </div>

    <!-- Content with floating image and drop cap -->
    <div class="prose prose-lg max-w-none mb-8">
        @if($post->featured_image)
            <div class="float-left mr-6 mb-4" x-data="{ open: false }">
                <img 
                    src="{{ $post->featured_image }}" 
                    alt="{{ $post->title }}" 
                    class="w-48 h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                    @click="open = true"
                    title="Click to enlarge"
                >

                <!-- Modal -->
                <div 
                    x-show="open" 
                    @click="open = false"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    style="display: none;"
                >
                    <div class="relative max-w-7xl max-h-full">
                        <img 
                            src="{{ $post->featured_image }}" 
                            alt="{{ $post->title }}" 
                            class="max-w-full max-h-screen h-auto rounded-lg"
                            @click.stop
                        >
                        <button 
                            @click="open = false" 
                            class="absolute top-4 right-4 text-white text-4xl font-bold hover:text-gray-300 leading-none"
                        >
                            &times;
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($post->sub_heading)
            <p class="text-xl text-gray-700 mb-4 font-medium">{{ $post->sub_heading }}</p>
        @endif
        
        @php
            // Remove the subheading from content if it appears at the start
            $content = $post->content;
            if ($post->sub_heading) {
                // Remove h3 with blog_sub_heading class containing the subheading
                $content = preg_replace('/<h3[^>]*class="[^"]*blog_sub_heading[^"]*"[^>]*>.*?<\/h3>/is', '', $content);
            }
        @endphp
        
        {!! $content !!}
    </div>

    <!-- Tags -->
    @if($post->tags->count() > 0)
        <div class="border-t pt-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Tags:</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</article>

@if(request('highlight'))
<script>
    // Highlight search terms when coming from search results
    document.addEventListener('DOMContentLoaded', function() {
        const searchTerm = {{ Js::from(request('highlight')) }};
        if (!searchTerm) return;
        
        const content = document.querySelector('.prose');
        if (!content) return;
        
        // Function to highlight text in a node
        function highlightText(node) {
            if (node.nodeType === 3) { // Text node
                const text = node.textContent;
                const regex = new RegExp('(' + searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                
                if (regex.test(text)) {
                    const span = document.createElement('span');
                    span.innerHTML = text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
                    node.parentNode.replaceChild(span, node);
                }
            } else if (node.nodeType === 1 && node.nodeName !== 'SCRIPT' && node.nodeName !== 'STYLE' && node.nodeName !== 'MARK') {
                // Element node (but not script, style, or already marked)
                Array.from(node.childNodes).forEach(highlightText);
            }
        }
        
        highlightText(content);
        
        // Scroll to first highlighted term
        const firstMark = content.querySelector('mark');
        if (firstMark) {
            firstMark.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endif
@endsection

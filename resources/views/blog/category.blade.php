@extends('layouts.app')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('blog.index') }}" class="text-primary-600 hover:text-primary-700 mb-4 inline-block">
            ← Back to all posts
        </a>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Category: {{ $category->name }}</h1>
        <p class="text-gray-600">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($posts as $post)
            <a href="{{ route('blog.show', $post) }}" class="block">
                <article class="card hover:shadow-lg transition-shadow duration-200 flex gap-4 h-full">
                    @if($post->featured_image)
                        <div class="flex-shrink-0">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-bold text-primary-600 mb-2">
                            {{ $post->title }}
                        </h2>

                        <div class="text-sm text-gray-500 mb-2">
                            {{ $post->published_at->format('Y-m-d') }}
                        </div>

                        @if($post->sub_heading)
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $post->sub_heading }}</p>
                        @endif
                    </div>
                </article>
            </a>
        @empty
            <p class="text-gray-600 col-span-2">No posts found in this category.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection

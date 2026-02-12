@extends('layouts.app')

@section('content')
<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Manage Posts</h1>
        <div class="flex gap-4">
            <a href="{{ route('admin.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + New Post
            </a>
            <a href="{{ route('blog.index') }}" class="text-primary-600 hover:text-primary-700">
                ← Back to blog
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-gray-900">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="mb-6">
        <form action="{{ route('admin.posts') }}" method="GET" class="max-w-md">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search by title:</label>
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Enter keyword..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.posts') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Clear
                    </a>
                @endif
            </div>
            @if(request('search'))
                <p class="mt-2 text-sm text-gray-600">Showing results for: <strong>{{ request('search') }}</strong></p>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="min-width: 800px;">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 150px;">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 130px;">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 220px; min-width: 220px;">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($posts as $post)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-md" title="{{ $post->title }}">{{ $post->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ $post->category?->name ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $post->published_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center justify-between whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.edit', $post) }}" class="text-primary-600 hover:text-primary-900">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('blog.show', $post) }}" class="text-gray-600 hover:text-gray-900" target="_blank">View</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post? This cannot be undone.');">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900">Delete</button></form>
                                </div>
                                <a href="{{ route('admin.share', $post) }}" class="ml-4 text-green-600 hover:text-green-900" title="Share">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection

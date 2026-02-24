@extends('layouts.app')

@section('content')
<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
        <a href="{{ route('admin.posts') }}" class="text-primary-600 hover:text-primary-700">
            ← Back to posts
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form id="edit-post-form" method="POST" action="{{ route('admin.update', $post) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title', $post->title) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sub Heading -->
            <div>
                <label for="sub_heading" class="block text-sm font-medium text-gray-700 mb-1">Sub Heading</label>
                <input 
                    type="text" 
                    name="sub_heading" 
                    id="sub_heading" 
                    value="{{ old('sub_heading', $post->sub_heading) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                >
                @error('sub_heading')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Author -->
            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                <input 
                    type="text" 
                    name="author" 
                    id="author" 
                    value="{{ old('author', $post->author) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                    required
                >
                @error('author')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Published Date -->
            <div>
                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Published Date</label>
                <input 
                    type="date" 
                    name="published_at" 
                    id="published_at" 
                    value="{{ old('published_at', $post->published_at->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                    required
                >
                @error('published_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured Image -->
            <div>
                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                @if($post->featured_image)
                    <div class="mb-2">
                        <img src="{{ $post->featured_image }}" alt="Current featured image" class="w-32 h-32 object-cover rounded">
                        <p class="text-sm text-gray-500 mt-1">Current image</p>
                    </div>
                @endif
                <input 
                    type="file" 
                    name="featured_image" 
                    id="featured_image"
                    accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                >
                <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image. Max 2MB. JPG, PNG, GIF supported.</p>
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select 
                    name="category_id" 
                    id="category_id" 
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="">-- No Category --</option>
                    @foreach($categories as $category)
                        <option 
                            value="{{ $category->id }}" 
                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div x-data="tagSelector()">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                
                <!-- Selected tags -->
                <div class="mb-3 flex flex-wrap gap-2">
                    <template x-for="tag in selectedTags" :key="tag.id">
                        <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm">
                            <span x-text="tag.name"></span>
                            <button type="button" @click="removeTag(tag.id)" class="hover:text-primary-900">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <input type="hidden" name="tags[]" :value="tag.id">
                        </div>
                    </template>
                </div>

                <!-- Tag input -->
                <div class="relative" @click.away="showDropdown = false">
                    <input 
                        type="text" 
                        x-model="search"
                        @input="filterTags"
                        @focus="showDropdown = true"
                        @keydown.escape="showDropdown = false"
                        @keydown.enter.prevent="addFirstMatch"
                        placeholder="Type to search or add new tag..."
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                    
                    <!-- Dropdown -->
                    <div 
                        x-show="showDropdown"
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto"
                        x-cloak
                    >
                        <!-- Existing tags -->
                        <template x-for="tag in filteredTags" :key="tag.id">
                            <button 
                                type="button"
                                @click="addTag(tag)"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm"
                                x-text="tag.name"
                            ></button>
                        </template>
                        
                        <!-- Create new tag option -->
                        <template x-if="search.length > 0 && !tagExists(search)">
                            <button 
                                type="button"
                                @click="createAndAddTag"
                                class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm border-t border-gray-200 text-primary-600 font-medium"
                            >
                                <span>+ Create "</span><span x-text="search"></span><span>"</span>
                            </button>
                        </template>
                        
                        <!-- No results -->
                        <template x-if="filteredTags.length === 0 && search.length === 0">
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Start typing to search tags...
                            </div>
                        </template>
                    </div>
                </div>
                
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div x-data="{ showMarkdownHelp: false }">
                <div class="flex justify-between items-center mb-1">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <button type="button" @click="showMarkdownHelp = true" class="text-xs text-primary-600 hover:text-primary-700">
                        Markdown Help
                    </button>
                </div>
                <p class="text-xs text-gray-500 mb-2">You can use Markdown formatting or the rich text editor</p>
                <div id="editor" style="height: 400px;"></div>
                <textarea name="content" id="content" style="display:none;" required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Markdown Help Modal -->
                <div x-show="showMarkdownHelp" 
                     @click="showMarkdownHelp = false"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;"
                >
                    <div @click.stop class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                        <div class="overflow-y-auto p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-2xl font-bold text-gray-900">Markdown Cheatsheet</h2>
                                <button @click="showMarkdownHelp = false" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Links</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm">[Link text](https://example.com)</code>
                                    <p class="text-sm text-gray-600 mt-1">Creates: <a href="#" class="text-blue-600 underline">Link text</a></p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Images</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm">![Alt text](https://example.com/image.jpg)</code>
                                    <p class="text-sm text-gray-600 mt-1">Embeds an image</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Text Formatting</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm mb-1">**bold text**</code>
                                    <code class="block bg-gray-100 p-2 rounded text-sm mb-1">*italic text*</code>
                                    <code class="block bg-gray-100 p-2 rounded text-sm">***bold and italic***</code>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Headings</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm mb-1"># Heading 1</code>
                                    <code class="block bg-gray-100 p-2 rounded text-sm mb-1">## Heading 2</code>
                                    <code class="block bg-gray-100 p-2 rounded text-sm">### Heading 3</code>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Lists</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium mb-1">Unordered:</p>
                                            <code class="block bg-gray-100 p-2 rounded text-sm whitespace-pre">- Item 1
- Item 2
- Item 3</code>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium mb-1">Ordered:</p>
                                            <code class="block bg-gray-100 p-2 rounded text-sm whitespace-pre">1. First
2. Second
3. Third</code>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Blockquotes</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm">&gt; This is a quote</code>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Code</h3>
                                    <code class="block bg-gray-100 p-2 rounded text-sm mb-1">`inline code`</code>
                                    <code class="block bg-gray-100 p-2 rounded text-sm whitespace-pre">```
code block
multiple lines
```</code>
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Note:</strong> The rich text editor (Quill) will convert your formatting to HTML automatically. 
                                        For new posts, you can use Markdown syntax directly in the content field if you prefer.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button @click="showMarkdownHelp = false" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-4 border-t">
                <a href="{{ route('blog.show', $post) }}" class="text-gray-600 hover:text-gray-900" target="_blank">
                    Preview post →
                </a>
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function tagSelector() {
        return {
            search: '',
            showDropdown: false,
            availableTags: @json($tags),
            selectedTags: @json($post->tags),
            filteredTags: [],
            
            init() {
                this.filterTags();
            },
            
            filterTags() {
                const searchLower = this.search.toLowerCase();
                this.filteredTags = this.availableTags
                    .filter(tag => 
                        !this.selectedTags.find(st => st.id === tag.id) &&
                        tag.name.toLowerCase().includes(searchLower)
                    )
                    .slice(0, 10);
            },
            
            addTag(tag) {
                if (!this.selectedTags.find(t => t.id === tag.id)) {
                    this.selectedTags.push(tag);
                    this.search = '';
                    this.filterTags();
                }
                this.showDropdown = false;
            },
            
            removeTag(tagId) {
                this.selectedTags = this.selectedTags.filter(t => t.id !== tagId);
                this.filterTags();
            },
            
            tagExists(name) {
                const nameLower = name.toLowerCase();
                return this.availableTags.some(tag => tag.name.toLowerCase() === nameLower);
            },
            
            async createAndAddTag() {
                if (!this.search.trim()) return;
                
                try {
                    const response = await fetch('/admin/tags', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ name: this.search.trim() })
                    });
                    
                    const newTag = await response.json();
                    this.availableTags.push(newTag);
                    this.addTag(newTag);
                } catch (error) {
                    console.error('Error creating tag:', error);
                    alert('Failed to create tag');
                }
            },
            
            addFirstMatch() {
                if (this.filteredTags.length > 0) {
                    this.addTag(this.filteredTags[0]);
                } else if (this.search.length > 0 && !this.tagExists(this.search)) {
                    this.createAndAddTag();
                }
            }
        }
    }
</script>

<!-- Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor {
        font-size: 16px;
        line-height: 1.6;
    }
</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'header': [2, 3, false] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });
    
    // Load existing content from textarea
    var existingContent = document.querySelector('#content').value;
    if (existingContent) {
        quill.clipboard.dangerouslyPasteHTML(existingContent);
    }
    
    // Sync editor content to hidden textarea on form submit
    document.querySelector('#edit-post-form').addEventListener('submit', function(e) {
        var html = quill.root.innerHTML;
        // Clean up Quill's empty paragraphs
        html = html.replace(/<p><br><\/p>/g, '');
        document.querySelector('#content').value = html;
        // Allow form to submit
        return true;
    });
</script>
@endsection

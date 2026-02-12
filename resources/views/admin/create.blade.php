@extends('layouts.app')

@section('content')
<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
        <a href="{{ route('admin.posts') }}" class="text-primary-600 hover:text-primary-700">
            ← Back to posts
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-bold">Validation Error:</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6" id="create-post-form">
        @csrf

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    value="{{ old('title') }}"
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
                    value="{{ old('sub_heading') }}"
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
                    value="{{ old('author', 'Peter Matthews') }}"
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
                    value="{{ old('published_at', date('Y-m-d')) }}"
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
                <input 
                    type="file" 
                    name="featured_image" 
                    id="featured_image"
                    accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                >
                <p class="mt-1 text-sm text-gray-500">Max 2MB. JPG, PNG, GIF supported.</p>
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <div id="editor" style="height: 400px;"></div>
                <textarea name="content" id="content" style="display:none;">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end items-center pt-4 border-t">
                <button 
                    type="submit" 
                    id="submit-button"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Create Post
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
            selectedTags: [],
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
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Quill editor...');
        
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
        
        console.log('Quill initialized:', quill);
        
        // Restore old content if validation failed
        var oldContent = document.querySelector('#content').value;
        if (oldContent) {
            quill.root.innerHTML = oldContent;
            console.log('Restored old content:', oldContent);
        }
        
        // Sync editor content to hidden textarea before form validation
        var form = document.querySelector('#create-post-form');
        console.log('Form found:', form);
        
        form.addEventListener('submit', function(e) {
            console.log('Form submit event fired!');
            
            // Sync Quill content to textarea
            var content = quill.root.innerHTML;
            document.querySelector('#content').value = content;
            
            // Debug: log what we're sending
            console.log('Quill content:', content);
            console.log('Quill text:', quill.getText());
            console.log('Content length:', quill.getLength());
            
            // Check if content is essentially empty (only contains <p><br></p> or similar)
            var isEmpty = quill.getText().trim().length === 0;
            if (isEmpty) {
                alert('Content field is empty. Please add some content.');
                e.preventDefault();
                return false;
            }
        }, true); // Use capture phase to run before validation
        
        console.log('Event listener attached');
    });
</script>
@endsection

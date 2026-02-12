@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Share: {{ $post->title }}</h1>
        <a href="{{ route('admin.posts') }}" class="text-primary-600 hover:text-primary-700">
            ← Back to posts
        </a>
    </div>

    <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded">
        <p class="font-medium">How to share:</p>
        <p class="text-sm">1. Screenshot the image for your platform<br>2. Copy any text you need<br>3. Add text overlay in your image editor<br>4. Post to social media</p>
    </div>

    <!-- Facebook -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook (1200 x 630)
        </h2>
        <div class="flex gap-6">
            @if($post->featured_image)
                <div class="relative">
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center" style="width: 400px; height: 210px;">
                        <img src="{{ $images['facebook'] }}" class="max-w-full max-h-full object-contain" id="facebook-img">
                    </div>
                    <button onclick="copyImageToClipboard('facebook-img', 1200, 630, 24, 'contain', this)" class="mt-1 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs inline-flex items-center gap-1" style="margin-left: auto; display: block; width: fit-content;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Copy Image
                    </button>
                </div>
            @endif
            <div class="flex-1 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->title }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->title) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @if($post->sub_heading)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-heading</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->sub_heading }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->sub_heading) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                    <div class="flex gap-2">
                        <div class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-800">
                            <span id="link-text-{{ $loop->parent->iteration ?? '1' }}">Read more from Peter Matthews at <a href="{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}" class="text-blue-600 hover:underline">www.henley.nz</a></span>
                        </div>
                        <button onclick="copyRichText('link-text-{{ $loop->parent->iteration ?? '1' }}', '{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instagram -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
            </svg>
            Instagram (1080 x 1080)
        </h2>
        <div class="flex gap-6">
            @if($post->featured_image)
                <div class="relative">
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden" style="width: 360px; height: 360px;">
                        <img src="{{ $images['instagram'] }}" class="w-full h-full object-cover" id="instagram-img">
                    </div>
                    <button onclick="copyImageToClipboard('instagram-img', 1080, 1080, 24, 'cover', this)" class="mt-1 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs inline-flex items-center gap-1" style="margin-left: auto; display: block; width: fit-content;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Copy Image
                    </button>
                </div>
            @endif
            <div class="flex-1 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->title }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->title) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @if($post->sub_heading)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-heading</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->sub_heading }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->sub_heading) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                    <div class="flex gap-2">
                        <div class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-800">
                            <span id="link-text-2">Read more from Peter Matthews at <a href="{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}" class="text-blue-600 hover:underline">www.henley.nz</a></span>
                        </div>
                        <button onclick="copyRichText('link-text-2', '{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LinkedIn -->
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
            LinkedIn (1200 x 627)
        </h2>
        <div class="flex gap-6">
            @if($post->featured_image)
                <div class="relative">
                    <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center" style="width: 400px; height: 209px;">
                        <img src="{{ $images['linkedin'] }}" class="max-w-full max-h-full object-contain" id="linkedin-img">
                    </div>
                    <button onclick="copyImageToClipboard('linkedin-img', 1200, 627, 24, 'contain', this)" class="mt-1 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs inline-flex items-center gap-1" style="margin-left: auto; display: block; width: fit-content;">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Copy Image
                    </button>
                </div>
            @endif
            <div class="flex-1 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->title }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->title) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @if($post->sub_heading)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-heading</label>
                    <div class="flex gap-2">
                        <input type="text" readonly value="{{ $post->sub_heading }}" class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50">
                        <button onclick="copyToClipboard('{{ addslashes($post->sub_heading) }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link</label>
                    <div class="flex gap-2">
                        <div class="flex-1 px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-800">
                            <span id="link-text-3">Read more from Peter Matthews at <a href="{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}" class="text-blue-600 hover:underline">www.henley.nz</a></span>
                        </div>
                        <button onclick="copyRichText('link-text-3', '{{ request()->getSchemeAndHttpHost() }}/post/{{ $post->id }}', this)" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        setTimeout(() => { btn.innerHTML = original; }, 1000);
    });
}

function copyRichText(elementId, url, btn) {
    const element = document.getElementById(elementId);
    const html = `Read more from Peter Matthews at <a href="${url}">www.henley.nz</a>`;
    const plainText = 'Read more from Peter Matthews at www.henley.nz';
    
    // Create a ClipboardItem with both HTML and plain text formats
    const blob = new Blob([html], { type: 'text/html' });
    const textBlob = new Blob([plainText], { type: 'text/plain' });
    
    navigator.clipboard.write([
        new ClipboardItem({
            'text/html': blob,
            'text/plain': textBlob
        })
    ]).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        setTimeout(() => { btn.innerHTML = original; }, 1000);
    }).catch(err => {
        console.error('Failed to copy rich text:', err);
        // Fallback to plain text
        navigator.clipboard.writeText(plainText).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            setTimeout(() => { btn.innerHTML = original; }, 1000);
        });
    });
}

async function copyImageToClipboard(imgId, width, height, radius, mode, btn) {
    try {
        const img = document.getElementById(imgId);
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        
        // Fill with white background for contain mode
        if (mode === 'contain') {
            ctx.fillStyle = '#f3f4f6'; // gray-100
            ctx.fillRect(0, 0, width, height);
        }
        
        // Draw rounded rectangle clip path
        ctx.beginPath();
        ctx.moveTo(radius, 0);
        ctx.lineTo(width - radius, 0);
        ctx.quadraticCurveTo(width, 0, width, radius);
        ctx.lineTo(width, height - radius);
        ctx.quadraticCurveTo(width, height, width - radius, height);
        ctx.lineTo(radius, height);
        ctx.quadraticCurveTo(0, height, 0, height - radius);
        ctx.lineTo(0, radius);
        ctx.quadraticCurveTo(0, 0, radius, 0);
        ctx.closePath();
        ctx.clip();
        
        const imgAspect = img.naturalWidth / img.naturalHeight;
        const canvasAspect = width / height;
        
        let sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight;
        
        if (mode === 'contain') {
            // Fit entire image within canvas (letterbox/pillarbox)
            sx = 0;
            sy = 0;
            sWidth = img.naturalWidth;
            sHeight = img.naturalHeight;
            
            if (imgAspect > canvasAspect) {
                // Image wider than canvas - fit to width
                dWidth = width;
                dHeight = width / imgAspect;
                dx = 0;
                dy = (height - dHeight) / 2;
            } else {
                // Image taller than canvas - fit to height
                dHeight = height;
                dWidth = height * imgAspect;
                dx = (width - dWidth) / 2;
                dy = 0;
            }
            
            ctx.drawImage(img, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight);
        } else {
            // Cover mode - fill canvas and crop
            if (imgAspect > canvasAspect) {
                // Image is wider than canvas - crop sides
                sHeight = img.naturalHeight;
                sWidth = sHeight * canvasAspect;
                sx = (img.naturalWidth - sWidth) / 2;
                sy = 0;
            } else {
                // Image is taller than canvas - crop top/bottom
                sWidth = img.naturalWidth;
                sHeight = sWidth / canvasAspect;
                sx = 0;
                sy = (img.naturalHeight - sHeight) / 2;
            }
            
            ctx.drawImage(img, sx, sy, sWidth, sHeight, 0, 0, width, height);
        }
        
        // Convert to blob and copy to clipboard
        canvas.toBlob(async (blob) => {
            await navigator.clipboard.write([
                new ClipboardItem({ 'image/png': blob })
            ]);
            
            // Show success feedback
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        });
    } catch (err) {
        console.error('Failed to copy image:', err);
        alert('Failed to copy image. Please try again.');
    }
}
</script>
@endsection

@extends('layouts.admin')

@section('header', 'Edit Blog')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 md:px-0">
    <div class="glass p-6 md:p-10 rounded-2xl md:rounded-[2.5rem] shadow-2xl border border-white/20">
        <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-8">
                <div>
                    <label class="block text-lg font-bold text-gray-800 dark:text-white mb-3">Blog Title</label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}" required 
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all duration-200 text-lg shadow-sm" 
                        placeholder="Update your story title...">
                    @error('title') <p class="text-red-500 text-sm mt-2 font-medium ml-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-800 dark:text-white mb-3">Featured Image</label>
                    <div class="relative group mb-4">
                        <div id="imagePreviewContainer" class="{{ $blog->image ? '' : 'hidden' }} mb-4 overflow-hidden rounded-3xl border-4 border-white shadow-lg aspect-video">
                            <img id="imagePreview" src="{{ $blog->image ? asset('storage/' . $blog->image) : '#' }}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <label for="imageInput" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-zinc-200 dark:border-slate-700 rounded-[2rem] cursor-pointer bg-zinc-50/30 dark:bg-slate-800/50 hover:bg-zinc-50 dark:hover:bg-slate-800 transition-all duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                <div class="w-12 h-12 mb-3 bg-zinc-100 dark:bg-zinc-900/30 rounded-xl flex items-center justify-center text-zinc-900 dark:text-zinc-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="mb-1 text-sm text-gray-700 dark:text-gray-300 font-bold">Change featured image</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Click to upload a new one</p>
                            </div>
                            <input id="imageInput" type="file" name="image" class="hidden" accept="image/*">
                        </label>
                    </div>
                    @error('image') <p class="text-red-500 text-sm mt-2 font-medium ml-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-lg font-bold text-gray-800 dark:text-white mb-3">Category / Tag</label>
                        <select name="category" class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all duration-200 text-lg shadow-sm">
                            <option value="">Select Category (Optional)</option>
                            <option value="PDF Tools" {{ old('category', $blog->category) == 'PDF Tools' ? 'selected' : '' }}>PDF Tools</option>
                            <option value="AI Tools" {{ old('category', $blog->category) == 'AI Tools' ? 'selected' : '' }}>AI Tools</option>
                            <option value="Business" {{ old('category', $blog->category) == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Comparisons" {{ old('category', $blog->category) == 'Comparisons' ? 'selected' : '' }}>Comparisons</option>
                        </select>
                        @error('category') <p class="text-red-500 text-sm mt-2 font-medium ml-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-lg font-bold text-gray-800 dark:text-white mb-3">Affiliate / Referral Link</label>
                        <input type="url" name="affiliate_link" value="{{ old('affiliate_link', $blog->affiliate_link) }}" 
                            class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all duration-200 text-lg shadow-sm" 
                            placeholder="https://partner-link.com/your-id">
                        @error('affiliate_link') <p class="text-red-500 text-sm mt-2 font-medium ml-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-lg font-bold text-gray-800 dark:text-white mb-3">Story Content</label>
                    <textarea name="content" id="editor" 
                        class="w-full px-6 py-4 rounded-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 focus:ring-4 focus:ring-zinc-950/10 focus:border-zinc-950 outline-none transition-all duration-200 shadow-sm leading-relaxed">{{ old('content', $blog->content) }}</textarea>
                    @error('content') <p class="text-red-500 text-sm mt-2 font-medium ml-2">{{ $message }}</p> @enderror
                </div>

                <style>
                    /* Custom CKEditor styling to match premium theme */
                    .ck-editor {
                        width: 100%;
                        margin-top: 0.5rem;
                    }
                    .ck.ck-editor__main>.ck-editor__editable {
                        min-height: 350px;
                        background: #fafafa !important;
                        border-color: #f1f5f9 !important;
                        border-bottom-left-radius: 1.5rem !important;
                        border-bottom-right-radius: 1.5rem !important;
                        padding: 1.5rem 2rem !important;
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-size: 1.1rem;
                        line-height: 1.8;
                        color: #334155;
                        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);
                    }
                    .ck.ck-editor__main>.ck-editor__editable:focus {
                        border-color: #18181b !important;
                        background: #ffffff !important;
                        box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.06) !important;
                        outline: none;
                    }
                    .ck.ck-toolbar {
                        background: #ffffff !important;
                        border-color: #f1f5f9 !important;
                        border-top-left-radius: 1.5rem !important;
                        border-top-right-radius: 1.5rem !important;
                        padding: 0.5rem 1rem !important;
                    }
                    .ck.ck-toolbar__items {
                        flex-wrap: wrap;
                    }
                    .ck.ck-button {
                        border-radius: 0.5rem !important;
                        color: #475569 !important;
                    }
                    .ck.ck-button:hover {
                        background: #f1f5f9 !important;
                    }
                    .ck.ck-button.ck-on {
                        background: #f4f4f5 !important;
                        color: #18181b !important;
                    }
                </style>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 btn-premium py-5 text-xl">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Update Story
                    </button>
                    <a href="{{ route('admin.blogs') }}" class="px-8 py-5 text-center text-gray-500 hover:text-gray-900 font-bold transition-colors">
                        Cancel Changes
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('imageInput').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');
            preview.src = URL.createObjectURL(file);
            container.classList.remove('hidden');
        }
    }

    // Initialize Classic CKEditor 5
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'undo', 'redo'
            ]
        })
        .then(editor => {
            // Form submit SweetAlert loading interceptor
            document.querySelector('form').addEventListener('submit', function() {
                Swal.fire({
                    title: 'Updating Story',
                    text: 'Uploading assets and saving changes...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
@endsection

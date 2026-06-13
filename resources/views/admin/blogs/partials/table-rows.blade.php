@forelse($blogs as $blog)
<tr class="group hover:bg-zinc-50 dark:hover:bg-slate-800/30 transition-all duration-200" id="blog-row-{{ $blog->id }}">
    <td class="px-4 md:px-8 py-4 md:py-6">
        <div class="flex items-center space-x-4">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" class="w-16 h-12 rounded-xl object-cover shadow-sm">
            @else
                <div class="w-16 h-12 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-image"></i>
                </div>
            @endif
            <div>
                <div class="font-bold text-gray-900 dark:text-white group-hover:text-zinc-700 transition-colors">{{ $blog->title }}</div>
                <div class="flex items-center space-x-2 mt-1">
                    <span class="text-xs text-gray-400 font-medium">{{ $blog->slug }}</span>
                    @if($blog->category)
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-zinc-100 text-zinc-800 dark:bg-zinc-900/30 dark:text-zinc-300">
                            {{ $blog->category }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </td>
    <td class="px-4 md:px-8 py-4 md:py-6 text-center">
        @if($blog->is_published)
            <span class="inline-flex items-center px-4 py-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-2xl text-xs font-black">
                <i class="fa-solid fa-circle-check me-2"></i> Published
            </span>
        @else
            <span class="inline-flex items-center px-4 py-2 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 rounded-2xl text-xs font-black">
                <i class="fa-solid fa-circle-dot me-2"></i> Draft
            </span>
        @endif
    </td>
    <td class="px-4 md:px-8 py-4 md:py-6 text-sm text-gray-500 font-medium">
        {{ $blog->created_at->format('M d, Y') }}
    </td>
    <td class="px-4 md:px-8 py-4 md:py-6 text-right">
        <div class="flex items-center justify-end space-x-2">
            <a href="{{ route('admin.blogs.edit', $blog) }}" class="p-3 text-zinc-800 hover:bg-zinc-100 rounded-xl transition-all duration-200" title="Edit Story">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <button onclick="deleteBlog({{ $blog->id }}, '{{ route('admin.blogs.delete', $blog) }}')" class="p-3 text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200" title="Delete Story">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="px-8 py-12 text-center text-gray-500 font-semibold">
        <div class="mb-2 text-2xl"><i class="fa-solid fa-folder-open text-gray-300"></i></div>
        No blogs found matching the search query.
    </td>
</tr>
@endforelse

{{-- Blog Cards Partial — used by both the main page and AJAX responses --}}
@forelse($blogs as $blog)
<article class="blog-card" data-id="{{ $blog->id }}">
    {{-- Thumbnail --}}
    <a href="{{ route('blog.show', $blog) }}" class="card-thumb">
        @if($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
        @else
            <div class="card-thumb-placeholder">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        @if($blog->category)
            <span class="card-cat-badge">{{ $blog->category }}</span>
        @endif
    </a>

    {{-- Body --}}
    <div class="card-body">
        <span class="card-date">
            <i class="fa-regular fa-calendar-days"></i>
            {{ $blog->created_at->format('M d, Y') }}
        </span>

        <h2 class="card-title">
            <a href="{{ route('blog.show', $blog) }}">{{ $blog->title }}</a>
        </h2>

        <p class="card-excerpt">{{ Str::limit(strip_tags($blog->content), 115) }}</p>

        <div class="card-footer flex items-center justify-between gap-4 pt-4 border-t border-zinc-100">
            <div class="flex items-center gap-2">
                <a href="{{ route('blog.show', $blog) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-zinc-900 text-white hover:bg-zinc-800 transition-colors shadow-sm">
                    Read Review
                </a>
                @if($blog->affiliate_link)
                    <a href="{{ $blog->affiliate_link }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl text-xs font-bold bg-zinc-50 text-zinc-800 border border-zinc-200 hover:bg-zinc-100 transition-colors flex items-center gap-1">
                        Try Tool <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                @endif
            </div>

            <div class="card-actions flex items-center gap-3">
                {{-- Like --}}
                <button
                    onclick="toggleLike({{ $blog->id }}, this)"
                    class="action-btn {{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'liked' : '' }} flex items-center gap-1 text-zinc-400 hover:text-zinc-600 transition-colors"
                    title="Like">
                    <svg class="w-4 h-4" fill="{{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="like-count text-xs font-bold">{{ $blog->likes()->count() }}</span>
                </button>

                {{-- Save --}}
                <button
                    onclick="toggleSave({{ $blog->id }}, this)"
                    class="action-btn {{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'saved text-zinc-900' : 'text-zinc-400 hover:text-zinc-600' }} transition-colors"
                    title="Save">
                    <svg class="w-4 h-4" fill="{{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </button>

                {{-- Share --}}
                <div class="share-wrap">
                    <button class="action-btn share-trigger text-zinc-400 hover:text-zinc-600 transition-colors" title="Share">
                        <i class="fa-solid fa-share-nodes text-sm"></i>
                    </button>
                    <div class="share-menu">
                        <button onclick="shareBlog('whatsapp','{{ route('blog.show',$blog) }}','{{ addslashes($blog->title) }}')" class="share-item whatsapp"><i class="fa-brands fa-whatsapp"></i></button>
                        <button onclick="shareBlog('facebook','{{ route('blog.show',$blog) }}','{{ addslashes($blog->title) }}')" class="share-item facebook"><i class="fa-brands fa-facebook"></i></button>
                        <button onclick="shareBlog('insta','{{ route('blog.show',$blog) }}','{{ addslashes($blog->title) }}')" class="share-item instagram"><i class="fa-brands fa-instagram"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
@empty
<div class="empty-state">
    <div class="empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
    <h3>No Reviews Found</h3>
    <p>Try a different search term or select another category.</p>
</div>
@endforelse

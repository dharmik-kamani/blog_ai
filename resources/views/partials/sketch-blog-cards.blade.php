{{-- Sketch Blog Cards Partial — Hand-drawn Polaroid Style --}}
@forelse($blogs as $blog)
<article class="sketch-card flex flex-col justify-between" data-id="{{ $blog->id }}">
    
    {{-- Polaroid Thumbnail --}}
    <div class="polaroid-frame relative">
        {{-- Tape overlay --}}
        <div class="masking-tape-top"></div>
        
        <a href="{{ route('blog.show', $blog) }}" class="polaroid-photo block overflow-hidden">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="sketch-image">
            @else
                <div class="polaroid-placeholder flex items-center justify-center">
                    <i class="fa-solid fa-pencil text-3xl opacity-30"></i>
                </div>
            @endif
        </a>
        
        {{-- Handwritten Category Caption --}}
        @if($blog->category)
            <div class="polaroid-caption text-center">
                <span class="handwritten-badge text-sm font-bold">{{ $blog->category }}</span>
            </div>
        @else
            <div class="polaroid-caption text-center">
                <span class="handwritten-badge text-sm font-bold">Doodle</span>
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="sketch-body flex-1 flex flex-col justify-between px-6 pb-6 pt-2">
        <div>
            <span class="sketch-date flex items-center gap-1.5 text-xs text-stone-500 font-bold mb-3">
                <i class="fa-solid fa-pen-nib"></i>
                {{ $blog->created_at->format('M d, Y') }}
            </span>

            <h2 class="sketch-title text-xl font-bold mb-3">
                <a href="{{ route('blog.show', $blog) }}" class="hover:text-stone-600 transition-colors">{{ $blog->title }}</a>
            </h2>

            <p class="sketch-excerpt text-sm text-stone-700 leading-relaxed mb-6 font-medium">
                {{ Str::limit(strip_tags($blog->content), 115) }}
            </p>
        </div>

        <div class="sketch-footer flex items-center justify-between gap-4 pt-4 border-t-2 border-dashed border-stone-300">
            {{-- CTA buttons --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('blog.show', $blog) }}" class="sketch-btn-primary text-xs font-bold px-4 py-2 bg-stone-900 text-stone-50 hover:bg-stone-800 transition-all shadow-sm">
                    Read Review
                </a>
                @if($blog->affiliate_link)
                    <a href="{{ $blog->affiliate_link }}" target="_blank" rel="noopener noreferrer" class="sketch-btn-secondary text-xs font-bold px-4 py-2 border-2 border-stone-800 hover:bg-stone-100 transition-all flex items-center gap-1">
                        Try Tool <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                @endif
            </div>

            {{-- Actions --}}
            <div class="sketch-actions flex items-center gap-3">
                {{-- Like --}}
                <button
                    onclick="toggleLike({{ $blog->id }}, this)"
                    class="action-btn {{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'liked text-red-500' : 'text-stone-400 hover:text-stone-600' }} flex items-center gap-1 transition-colors"
                    title="Like">
                    <i class="fa-{{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'solid' : 'regular' }} fa-heart text-sm"></i>
                    <span class="like-count text-xs font-bold">{{ $blog->likes()->count() }}</span>
                </button>

                {{-- Save --}}
                <button
                    onclick="toggleSave({{ $blog->id }}, this)"
                    class="action-btn {{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'saved text-stone-900' : 'text-stone-400 hover:text-stone-600' }} transition-colors"
                    title="Save">
                    <i class="fa-{{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'solid' : 'regular' }} fa-bookmark text-sm"></i>
                </button>

                {{-- Share --}}
                <div class="share-wrap">
                    <button class="action-btn share-trigger text-stone-400 hover:text-stone-600 transition-colors" title="Share">
                        <i class="fa-solid fa-share-nodes text-sm"></i>
                    </button>
                    <div class="share-menu sketch-share-menu">
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
<div class="sketch-empty-state text-center py-12 px-6 border-2 border-dashed border-stone-400 rounded-3xl">
    <div class="sketch-empty-icon text-5xl text-stone-400 mb-4"><i class="fa-solid fa-eraser"></i></div>
    <h3 class="text-xl font-bold text-stone-800 mb-2">Pencil Rubbed Clean!</h3>
    <p class="text-stone-500 text-sm">No reviews found matching that search/category. Try sketching a different keyword.</p>
</div>
@endforelse

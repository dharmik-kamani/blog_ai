@extends('layouts.frontend')

@section('content')
<style>
    .article-container {
        background: #ffffff;
        border: 1px solid #e4e4e7;
        border-radius: 32px;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.02), 0 5px 15px -5px rgba(0, 0, 0, 0.01);
        padding: 48px;
    }
    @media (max-width: 768px) {
        .article-container {
            padding: 24px 20px;
            border-radius: 24px;
        }
    }
    .article-image {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 32px;
        border: 1px solid #e4e4e7;
        box-shadow: 0 8px 20px rgba(0,0,0,0.01);
    }
    .article-image img {
        width: 100%;
        height: 460px;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        .article-image img {
            height: 260px;
        }
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: #71717a;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 24px;
    }
    .back-link:hover {
        color: #18181b;
        transform: translateX(-4px);
    }
    /* Interaction bar */
    .interaction-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-top: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 36px;
    }
    .int-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.2s ease;
        padding: 8px 16px;
        border-radius: 12px;
    }
    .int-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .int-btn.liked {
        color: #ef4444;
        background: #fef2f2;
    }
    .int-btn.liked:hover {
        background: #fee2e2;
    }
    .int-btn.saved {
        color: #1e293b;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
    }
    .int-btn.saved:hover {
        background: #e2e8f0;
    }

    /* Premium Affiliate Box */
    .partner-card {
        background: #f8fafc;
        border: 1.5px dashed #475569;
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }
    .partner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #1e293b;
    }
    @media (min-width: 768px) {
        .partner-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .partner-badge {
        display: inline-block;
        background: #e2e8f0;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 4px 12px;
        border-radius: 9999px;
        margin-bottom: 8px;
    }
    .partner-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .partner-desc {
        color: #475569;
        font-size: 14.5px;
        line-height: 1.5;
        max-width: 480px;
    }
    .partner-action {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    @media (min-width: 768px) {
        .partner-action {
            align-items: flex-end;
        }
    }
    .partner-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #1e293b;
        color: #ffffff;
        font-weight: 800;
        font-size: 15px;
        padding: 14px 28px;
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(30, 41, 59, 0.15);
    }
    .partner-btn:hover {
        background: #334155;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 41, 59, 0.25);
    }
    .partner-disclaimer {
        font-size: 11px;
        color: #64748b;
        font-style: italic;
    }

    /* Content styling */
    .blog-body-text {
        font-size: 17px;
        line-height: 1.85;
        color: #334155;
    }
    .blog-body-text p {
        margin-bottom: 24px;
    }

    /* Share Dropdown menu */
    .share-wrap {
        position: relative;
    }
    .share-menu {
        position: absolute;
        bottom: calc(100% + 8px);
        right: 0;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 6px;
        display: none;
        flex-direction: row;
        gap: 6px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        z-index: 10;
    }
    /* Invisible bridge to prevent losing hover when moving cursor across the gap */
    .share-menu::after {
        content: '';
        position: absolute;
        bottom: -12px;
        left: 0;
        right: 0;
        height: 12px;
        background: transparent;
    }
    .share-wrap:hover .share-menu,
    .share-wrap.active .share-menu {
        display: flex;
    }
    .share-item {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .share-item.whatsapp { color: #25d366; background: #e8fbf0; }
    .share-item.whatsapp:hover { background: #d0f7df; }
    .share-item.facebook { color: #1877f2; background: #e8f3ff; }
    .share-item.facebook:hover { background: #d0e7ff; }
    .share-item.instagram { color: #e1306c; background: #ffebee; }
    .share-item.instagram:hover { background: #ffcdd2; }
</style>

<div class="max-w-4xl mx-auto py-12">
    <a href="{{ route('home') }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to Explore</span>
    </a>

    <article class="article-container fade-in">
        @if($blog->image)
            <div class="article-image">
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
            </div>
        @endif

        <div class="mb-4">
            <span class="text-sm text-gray-400 font-bold uppercase tracking-widest">
                <i class="fa-regular fa-calendar-days me-1"></i>
                {{ $blog->created_at->format('M d, Y') }}
            </span>
        </div>

        <h1 class="text-4xl md:text-5xl font-black mb-8 leading-tight text-slate-800">
            {{ $blog->title }}
        </h1>

        <!-- Interaction Bar -->
        <div class="interaction-bar">
            <div class="flex items-center space-x-4">
                <button onclick="toggleLike({{ $blog->id }}, this)" 
                        class="int-btn {{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'liked' : '' }}">
                    <i class="fa-{{ auth()->check() && $blog->isLikedBy(auth()->user()) ? 'solid' : 'regular' }} fa-heart text-lg"></i>
                    <span class="like-count">{{ $blog->likes()->count() }}</span>
                </button>

                <button onclick="toggleSave({{ $blog->id }}, this)" 
                        class="int-btn {{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'saved' : '' }}">
                    <i class="fa-{{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'solid' : 'regular' }} fa-bookmark text-lg"></i>
                    <span>{{ auth()->check() && $blog->isSavedBy(auth()->user()) ? 'Saved' : 'Save' }}</span>
                </button>
            </div>

            <div class="flex items-center space-x-3">
                <span class="text-gray-400 text-xs font-black uppercase tracking-wider hidden sm:inline">Share Review:</span>
                <div class="share-wrap">
                    <button class="int-btn share-trigger">
                        <i class="fa-solid fa-share-nodes text-lg"></i>
                    </button>
                    <div class="share-menu">
                        <button onclick="shareBlog('whatsapp', '{{ route('blog.show', $blog) }}', '{{ addslashes($blog->title) }}')" class="share-item whatsapp"><i class="fa-brands fa-whatsapp"></i></button>
                        <button onclick="shareBlog('facebook', '{{ route('blog.show', $blog) }}', '{{ addslashes($blog->title) }}')" class="share-item facebook"><i class="fa-brands fa-facebook"></i></button>
                        <button onclick="shareBlog('insta', '{{ route('blog.show', $blog) }}', '{{ addslashes($blog->title) }}')" class="share-item instagram"><i class="fa-brands fa-instagram"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affiliate CTA Box -->
        @if($blog->affiliate_link)
            <div class="partner-card">
                <div>
                    <span class="partner-badge">
                        {{ $blog->category ?? 'AI Tool Partner' }}
                    </span>
                    <h3 class="partner-title">Try {{ $blog->title }}</h3>
                    <p class="partner-desc">
                        Get access to this tool using our partner referral link. Support our research and reviews with no extra cost to you!
                    </p>
                </div>
                <div class="partner-action">
                    <a href="{{ $blog->affiliate_link }}" target="_blank" rel="noopener noreferrer" class="partner-btn">
                        <span>Visit & Try Tool</span>
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                    <span class="partner-disclaimer">* We may earn a commission on qualifying purchases</span>
                </div>
            </div>
        @endif

        <!-- Blog Body Content -->
        <div class="blog-body-text prose prose-lg max-w-none text-slate-700 leading-relaxed">
            {!! $blog->content !!}
        </div>
    </article>
</div>
@endsection

@extends('layouts.frontend')

@section('content')
<style>
    /* Card layout & premium borders */
    .blog-card {
        background: #ffffff;
        border: 1px solid #e4e4e7;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(24, 24, 27, 0.05), 0 10px 10px -5px rgba(24, 24, 27, 0.02);
        border-color: #a1a1aa;
    }
    .card-thumb {
        position: relative;
        display: block;
        height: 220px;
        overflow: hidden;
        background: #fafafa;
    }
    .card-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .blog-card:hover .card-thumb img {
        transform: scale(1.05);
    }
    .card-thumb-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #f4f4f5 0%, #e4e4e7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a1a1aa;
    }
    .card-thumb-placeholder svg {
        width: 48px;
        height: 48px;
    }
    .card-cat-badge {
        position: absolute;
        top: 16px;
        right: 16px;
        background: #18181b;
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .card-body {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 12px;
    }
    .card-title {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.4;
        color: #18181b;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s ease;
    }
    .card-title a {
        text-decoration: none;
        color: inherit;
    }
    .card-title a:hover {
        color: #71717a;
    }
    .card-excerpt {
        font-size: 14.5px;
        color: #52525b;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .card-cta-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: #18181b;
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        padding: 13px 18px;
        border-radius: 14px;
        text-decoration: none;
        margin-bottom: 16px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(24, 24, 27, 0.15);
    }
    .card-cta-btn:hover {
        background: #27272a;
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(24, 24, 27, 0.25);
    }
    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        margin-top: auto;
    }
    .card-read-link {
        font-size: 13px;
        font-weight: 700;
        color: #71717a;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: color 0.2s ease;
        text-decoration: none;
    }
    .card-read-link:hover {
        color: #18181b;
    }
    .card-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #a1a1aa;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        padding: 4px;
    }
    .action-btn:hover {
        color: #52525b;
    }
    .action-btn.liked {
        color: #ef4444;
    }
    .action-btn.saved {
        color: #18181b;
    }
    .like-count {
        font-size: 12px;
        font-weight: 700;
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
        border: 1px solid #e4e4e7;
        border-radius: 12px;
        padding: 6px;
        display: none;
        flex-direction: row;
        gap: 6px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
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

    /* Empty state design */
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: #ffffff;
        border: 2px dashed #e4e4e7;
        border-radius: 32px;
        max-width: 500px;
        margin: 40px auto;
        width: 100%;
        grid-column: 1 / -1;
    }
    .empty-icon {
        font-size: 48px;
        color: #d4d4d8;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 20px;
        font-weight: 800;
        color: #18181b;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #71717a;
        font-size: 14px;
    }
</style>

<!-- Sketchbook Link Banner -->
<div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/80 rounded-3xl p-4 mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left shadow-sm">
    <div class="flex items-center gap-3">
        <span class="text-2xl">✏️</span>
        <div>
            <h4 class="font-extrabold text-sm text-amber-900">Try our new Hand-Drawn Sketchbook Theme!</h4>
            <p class="text-xs text-amber-700/90 font-medium">Explore the entire reviews catalog drawn as graphite on grid paper.</p>
        </div>
    </div>
    <a href="{{ route('home.sketch') }}" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-amber-950 hover:bg-amber-900 text-white transition-all shadow-sm flex items-center gap-1.5">
        🎨 View Sketchbook Theme <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
</div>

<header class="text-center py-12 md:py-16 fade-in">
    <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
        Discover <span class="bg-clip-text text-transparent bg-gradient-to-r from-zinc-700 to-black">Top AI Tools</span> <br>
        & Expert Reviews
    </h1>
    <p class="text-lg md:text-xl text-zinc-500 max-w-2xl mx-auto">
        Find the best AI resources, check unbiased comparisons, and use our links to get exclusive deals!
    </p>
</header>

<!-- Full Width Filter Bar -->
<div class="bg-white border border-zinc-200/80 rounded-3xl p-6 shadow-sm mb-10">
    <form id="filterForm" action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-6" onsubmit="event.preventDefault();">
        <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'All') }}">
        
        <!-- Category scrollable tags -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none flex-1 max-w-full">
            @foreach(['All', 'PDF Tools', 'AI Tools', 'Business', 'Comparisons'] as $cat)
                <button type="button" onclick="selectCategory('{{ $cat }}')" data-cat="{{ $cat }}"
                    class="cat-btn px-5 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all duration-200 border {{ request('category', 'All') === $cat ? 'bg-zinc-900 text-white border-zinc-900 shadow-sm' : 'bg-zinc-50 text-zinc-500 border-zinc-100 hover:border-zinc-300 hover:bg-zinc-100' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        <!-- Search input -->
        <div class="relative w-full md:w-80 shrink-0">
            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                class="w-full pl-11 pr-5 py-3 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-900/5 focus:border-zinc-900 outline-none transition-all text-xs font-semibold text-zinc-700 shadow-inner" 
                placeholder="Search reviews...">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
        </div>
    </form>
</div>

<!-- Blog Grid -->
<div id="blogCardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-opacity duration-200">
    @include('partials.blog-cards')
</div>

<div id="paginationContainer" class="mt-12">
    {{ $blogs->links() }}
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;

    // Handle typing inside search box with debounce
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            fetchBlogs();
        }, 400);
    });

    // Select category and update buttons layout
    function selectCategory(cat) {
        $('#categoryInput').val(cat);
        $('.cat-btn').each(function() {
            if ($(this).attr('data-cat') === cat) {
                $(this).addClass('bg-zinc-900 text-white border-zinc-900 shadow-sm').removeClass('bg-zinc-50 text-zinc-500 border-zinc-100 hover:border-zinc-300 hover:bg-zinc-100');
            } else {
                $(this).removeClass('bg-zinc-900 text-white border-zinc-900 shadow-sm').addClass('bg-zinc-50 text-zinc-500 border-zinc-100 hover:border-zinc-300 hover:bg-zinc-100');
            }
        });
        fetchBlogs();
    }

    // Intercept pagination link clicks for AJAX
    $(document).on('click', '#paginationContainer a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            let urlParams = new URLSearchParams(url.split('?')[1]);
            let page = urlParams.get('page');
            fetchBlogs(page);
        }
    });

    // Fetch blogs using AJAX
    function fetchBlogs(page = 1) {
        let search = $('#searchInput').val();
        let category = $('#categoryInput').val();
        
        $('#blogCardsContainer').addClass('opacity-50 pointer-events-none');
        
        $.ajax({
            url: "{{ route('home') }}",
            type: 'GET',
            data: {
                search: search,
                category: category,
                page: page
            },
            success: function(response) {
                $('#blogCardsContainer').removeClass('opacity-50 pointer-events-none');
                $('#blogCardsContainer').html(response.html);
                $('#paginationContainer').html(response.pagination);
                
                // Update address bar URL without reloading the page
                let params = new URLSearchParams();
                if (search) params.set('search', search);
                if (category && category !== 'All') params.set('category', category);
                if (page && page > 1) params.set('page', page);
                
                let newQuery = params.toString();
                let newUrl = window.location.pathname + (newQuery ? '?' + newQuery : '');
                window.history.pushState({path: newUrl}, '', newUrl);
            },
            error: function() {
                $('#blogCardsContainer').removeClass('opacity-50 pointer-events-none');
                console.error('Failed to load blog posts.');
            }
        });
    }

    // Support browser back/forward buttons with AJAX
    window.onpopstate = function(e) {
        let urlParams = new URLSearchParams(window.location.search);
        let search = urlParams.get('search') || '';
        let category = urlParams.get('category') || 'All';
        let page = urlParams.get('page') || 1;

        $('#searchInput').val(search);
        $('#categoryInput').val(category);

        $('.cat-btn').each(function() {
            if ($(this).attr('data-cat') === category) {
                $(this).addClass('bg-zinc-900 text-white border-zinc-900 shadow-sm').removeClass('bg-zinc-50 text-zinc-500 border-zinc-100 hover:border-zinc-300 hover:bg-zinc-100');
            } else {
                $(this).removeClass('bg-zinc-900 text-white border-zinc-900 shadow-sm').addClass('bg-zinc-50 text-zinc-500 border-zinc-100 hover:border-zinc-300 hover:bg-zinc-100');
            }
        });

        // Fetch blogs from current URL state
        $('#blogCardsContainer').addClass('opacity-50 pointer-events-none');
        $.ajax({
            url: "{{ route('home') }}",
            type: 'GET',
            data: {
                search: search,
                category: category,
                page: page
            },
            success: function(response) {
                $('#blogCardsContainer').removeClass('opacity-50 pointer-events-none');
                $('#blogCardsContainer').html(response.html);
                $('#paginationContainer').html(response.pagination);
            },
            error: function() {
                $('#blogCardsContainer').removeClass('opacity-50 pointer-events-none');
            }
        });
    };
</script>
@endpush

@extends('layouts.frontend')

@section('content')
<!-- Sketchbook Fonts & Custom Theme Styles -->
<link href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Caveat:wght@600;700&family=Special+Elite&display=swap" rel="stylesheet">

<style>
    /* Global Background Graph Paper override */
    body {
        background-color: #f7f4eb !important;
        background-image: 
            linear-gradient(90deg, rgba(139, 90, 43, 0.055) 1px, transparent 1px),
            linear-gradient(rgba(139, 90, 43, 0.055) 1px, transparent 1px) !important;
        background-size: 24px 24px !important;
        font-family: 'Special Elite', 'Courier New', Courier, monospace;
        color: #2e2a24;
    }

    /* Red Notebook Margin line */
    .notebook-container {
        position: relative;
    }
    @media (min-width: 1024px) {
        .notebook-container::before {
            content: '';
            position: fixed;
            top: 0;
            bottom: 0;
            left: 90px;
            width: 2px;
            background: rgba(239, 68, 68, 0.25);
            pointer-events: none;
            z-index: 100;
        }
    }

    /* Banner style */
    .sketch-banner {
        font-family: 'Architects Daughter', cursive;
        border: 2px solid #2e2a24;
        background: #fffdf5;
        border-radius: 8px 255px 12px 225px/15px 225px 12px 255px;
        box-shadow: 3px 4px 0px #2e2a24;
        transition: transform 0.2s ease;
    }
    .sketch-banner:hover {
        transform: rotate(-0.5deg) scale(1.01);
    }

    /* Custom wobbly card styling */
    .sketch-card {
        background: #ffffff;
        border: 2.5px solid #2e2a24;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        box-shadow: 4px 6px 0px #2e2a24;
        padding: 0;
        transition: all 0.25s ease;
        position: relative;
    }
    .sketch-card:hover {
        transform: translate(-3px, -3px);
        box-shadow: 7px 9px 0px #2e2a24;
    }
    /* Double sketch line effect */
    .sketch-card::before {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        right: 3px;
        bottom: 3px;
        border: 1px solid rgba(46, 42, 36, 0.15);
        border-radius: 240px 20px 210px 25px/20px 210px 25px 240px;
        pointer-events: none;
    }

    /* Polaroid Picture effect */
    .polaroid-frame {
        background: #ffffff;
        padding: 14px;
        border-bottom: 2.5px solid #2e2a24;
        margin-bottom: 12px;
        transform: rotate(-1.5deg);
        transition: transform 0.3s ease;
        box-shadow: 2px 3px 8px rgba(0,0,0,0.04);
    }
    .sketch-card:hover .polaroid-frame {
        transform: rotate(1.5deg);
    }
    .polaroid-photo {
        height: 180px;
        border: 2px solid #2e2a24;
        background: #faf8f5;
    }
    .sketch-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(30%) sepia(20%) contrast(110%);
    }
    .polaroid-caption {
        padding-top: 10px;
        font-family: 'Architects Daughter', cursive;
        color: #2e2a24;
        letter-spacing: 0.5px;
    }
    .polaroid-placeholder {
        height: 100%;
        background: linear-gradient(135deg, #f0ede6 0%, #e2ded5 100%);
        color: #8c857b;
    }

    /* Masking tape on Polaroid top */
    .masking-tape-top {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%) rotate(2.5deg);
        width: 85px;
        height: 22px;
        background: rgba(232, 222, 192, 0.7);
        border-left: 2px dashed rgba(0,0,0,0.08);
        border-right: 2px dashed rgba(0,0,0,0.08);
        box-shadow: 0px 1px 4px rgba(0,0,0,0.04);
        z-index: 10;
        pointer-events: none;
    }

    /* Sketch custom button styles */
    .sketch-btn-primary {
        font-family: 'Architects Daughter', cursive;
        border: 2px solid #2e2a24;
        border-radius: 9999px 12px 9999px 15px/15px 9999px 12px 9999px;
        background: #2e2a24;
        color: #f7f4eb;
        transition: all 0.2s ease;
    }
    .sketch-btn-primary:hover {
        background: #ffffff;
        color: #2e2a24;
        transform: scale(1.03);
    }
    .sketch-btn-secondary {
        font-family: 'Architects Daughter', cursive;
        border: 2px solid #2e2a24;
        border-radius: 12px 255px 15px 225px/225px 15px 255px 12px;
        background: transparent;
        color: #2e2a24;
        transition: all 0.2s ease;
    }
    .sketch-btn-secondary:hover {
        background: rgba(46, 42, 36, 0.05);
        transform: scale(1.03);
    }

    /* Sketchy inputs */
    .sketch-input {
        font-family: 'Architects Daughter', cursive;
        border: 2.5px solid #2e2a24 !important;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px !important;
        background: #ffffff !important;
        box-shadow: inset 1px 2px 5px rgba(0,0,0,0.04) !important;
    }
    .sketch-input:focus {
        border-color: #5e5447 !important;
        box-shadow: 2px 3px 0px #2e2a24 !important;
        outline: none !important;
    }

    /* Category Filter tags styled as sketches */
    .sketch-cat-btn {
        font-family: 'Architects Daughter', cursive;
        border: 2.5px solid #2e2a24;
        border-radius: 255px 15px 225px 15px/15px 225px 15px 255px;
        background: #ffffff;
        transition: all 0.2s ease;
        box-shadow: 3px 4px 0px #2e2a24;
    }
    .sketch-cat-btn.active {
        background: #2e2a24;
        color: #f7f4eb;
        box-shadow: none;
        transform: translate(3px, 4px);
    }
    .sketch-cat-btn:hover:not(.active) {
        background: rgba(46, 42, 36, 0.05);
        transform: translateY(-2px);
        box-shadow: 4px 5px 0px #2e2a24;
    }

    /* Handwritten Dates & Details */
    .sketch-date {
        font-family: 'Architects Daughter', cursive;
    }
    .handwritten-badge {
        font-family: 'Architects Daughter', cursive;
    }

    /* Share menu styled as sketchy box */
    .sketch-share-menu {
        border: 2px solid #2e2a24 !important;
        border-radius: 12px !important;
        box-shadow: 3px 4px 0px #2e2a24 !important;
        background: #ffffff !important;
    }
    .sketch-share-menu::after {
        bottom: -15px !important;
        height: 15px !important;
    }

    /* Sketch pagination layout overrides */
    #paginationContainer nav {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    #paginationContainer span, #paginationContainer a {
        font-family: 'Architects Daughter', cursive;
        border: 2px solid #2e2a24 !important;
        border-radius: 10px 15px 8px 12px/12px 8px 15px 10px !important;
        padding: 8px 16px !important;
        background: #ffffff !important;
        color: #2e2a24 !important;
        box-shadow: 2px 3px 0px #2e2a24;
        transition: all 0.2s ease;
        margin: 0 4px;
    }
    #paginationContainer a:hover {
        background: rgba(46, 42, 36, 0.05) !important;
        transform: translateY(-1px);
        box-shadow: 3px 4px 0px #2e2a24;
    }
    #paginationContainer .active span {
        background: #2e2a24 !important;
        color: #f7f4eb !important;
        box-shadow: none !important;
        transform: translate(2px, 3px);
    }
</style>

<div class="notebook-container max-w-7xl mx-auto py-6">
    
    <!-- Theme Toggle Banner -->
    <div class="sketch-banner p-4 mb-10 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
        <div class="flex items-center gap-3">
            <span class="text-3xl">🎨</span>
            <div>
                <h4 class="font-black text-lg">You are exploring the Sketchbook Theme!</h4>
                <p class="text-sm text-stone-600 font-medium">All designs, cards, and text are drawn like pencil on grid paper.</p>
            </div>
        </div>
        <a href="{{ route('home') }}" class="sketch-btn-primary px-6 py-2.5 text-xs font-bold whitespace-nowrap">
            🖥️ Switch to Modern Theme
        </a>
    </div>

    <!-- Header Section -->
    <header class="text-center py-12 md:py-16">
        <h1 class="sketch-header-title text-4xl sm:text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
            Discover <span class="border-b-4 border-dashed border-stone-600 pb-1">Top AI Tools</span> <br>
            & Pencil-Sketched Reviews
        </h1>
        <p class="sketch-header-subtitle text-sm md:text-base text-stone-600 max-w-2xl mx-auto leading-relaxed">
            [Drafted on Sepia Paper: Authentic, Unbiased reviews to upgrade your creative productivity.]
        </p>
    </header>

    <!-- Sketchy Filter Capsule -->
    <div class="border-2 border-stone-800 rounded-3xl p-6 bg-white/40 backdrop-blur-sm shadow-sm mb-10">
        <form id="filterForm" action="{{ route('home.sketch') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-6" onsubmit="event.preventDefault();">
            <input type="hidden" name="category" id="categoryInput" value="{{ request('category', 'All') }}">
            
            <!-- Sketch tags scroll -->
            <div class="flex items-center gap-3 overflow-x-auto pb-2 md:pb-0 scrollbar-none flex-1 max-w-full">
                @foreach(['All', 'PDF Tools', 'AI Tools', 'Business', 'Comparisons'] as $cat)
                    <button type="button" onclick="selectCategory('{{ $cat }}')" data-cat="{{ $cat }}"
                        class="sketch-cat-btn px-5 py-2.5 text-xs font-bold whitespace-nowrap {{ request('category', 'All') === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- Search box -->
            <div class="relative w-full md:w-80 shrink-0">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                    class="sketch-input w-full pl-11 pr-5 py-3 text-xs font-semibold text-stone-700 outline-none transition-all" 
                    placeholder="Sketch a review name...">
                <i class="fa-solid fa-pencil absolute left-4 top-1/2 -translate-y-1/2 text-stone-500 text-xs"></i>
            </div>
        </form>
    </div>

    <!-- Sketch Blog Grid -->
    <div id="blogCardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 transition-opacity duration-200">
        @include('partials.sketch-blog-cards')
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" class="mt-12">
        {{ $blogs->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout;

    // Search input typing with debounce
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            fetchBlogs();
        }, 400);
    });

    // Category click handler
    function selectCategory(cat) {
        $('#categoryInput').val(cat);
        $('.sketch-cat-btn').each(function() {
            if ($(this).attr('data-cat') === cat) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
        fetchBlogs();
    }

    // Intercept pagination clicks for AJAX loading
    $(document).on('click', '#paginationContainer a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (url) {
            let urlParams = new URLSearchParams(url.split('?')[1]);
            let page = urlParams.get('page');
            fetchBlogs(page);
        }
    });

    // Fetch blogs via AJAX (routes to home.sketch)
    function fetchBlogs(page = 1) {
        let search = $('#searchInput').val();
        let category = $('#categoryInput').val();
        
        $('#blogCardsContainer').addClass('opacity-50 pointer-events-none');
        
        $.ajax({
            url: "{{ route('home.sketch') }}",
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
                
                // Update browser address bar
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
                console.error('Failed to load sketchy blog posts.');
            }
        });
    }

    // Handle back/forward actions
    window.onpopstate = function(e) {
        let urlParams = new URLSearchParams(window.location.search);
        let search = urlParams.get('search') || '';
        let category = urlParams.get('category') || 'All';
        let page = urlParams.get('page') || 1;

        $('#searchInput').val(search);
        $('#categoryInput').val(category);

        $('.sketch-cat-btn').each(function() {
            if ($(this).attr('data-cat') === category) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });

        $('#blogCardsContainer').addClass('opacity-50 pointer-events-none');
        $.ajax({
            url: "{{ route('home.sketch') }}",
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

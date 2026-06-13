<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Explore Amazing Stories</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons & Scripts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#18181b',
                        secondary: '#3f3f46',
                        accent: '#71717a',
                    }
                }
            }
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); border: 1px solid rgba(24, 24, 27, 0.08); border-radius: 24px; }
        .btn-premium { background: #18181b; color: white; font-weight: 700; border-radius: 16px; border: 1px solid #18181b; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; }
        .btn-premium:hover { transform: translateY(-2px); background: #27272a; border-color: #27272a; box-shadow: 0 10px 20px rgba(24, 24, 27, 0.15); opacity: 0.95; }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#fcfcfd] dark:bg-slate-900 text-slate-900">
    <!-- Navbar -->
    <nav class="sticky top-4 z-50 bg-white/80 backdrop-blur-xl border border-zinc-200/50 rounded-full mx-4 my-6 px-8 py-3.5 flex justify-between items-center max-w-7xl lg:mx-auto shadow-[0_8px_30px_rgb(0,0,0,0.03)]">
        <a href="{{ route('home') }}" class="text-2xl font-black text-zinc-900 tracking-tight hover:opacity-80 transition-opacity">
            BlogVibe
        </a>
        
        <!-- Centered Navigation capsule (Desktop only) -->
        <div class="hidden md:flex items-center bg-zinc-100/60 border border-zinc-200/20 rounded-full p-1 space-x-1">
            <a href="{{ route('home') }}" class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}">
                Explore
            </a>
            @auth
                <a href="{{ route('blogs.saved') }}" class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 {{ request()->routeIs('blogs.saved') ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}">
                    Saved
                </a>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard*') ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-900' }}">
                        Admin
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="hidden md:flex items-center space-x-5">
            @auth
                <!-- Profile Hover Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-9 h-9 rounded-full border border-zinc-300 object-cover shadow-sm">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=18181b&color=fff" class="w-9 h-9 rounded-full border border-zinc-300 shadow-sm">
                        @endif
                        <span class="text-zinc-700 hover:text-zinc-900 font-bold text-xs transition-colors hidden lg:block">{{ auth()->user()->name }}</span>
                    </button>
                    
                    <div class="absolute right-0 w-48 mt-0 pt-2 hidden group-hover:block fade-in z-[60]">
                        <div class="glass shadow-2xl border border-white/20 rounded-2xl p-2 overflow-hidden bg-white">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-slate-700 hover:bg-zinc-50 hover:text-zinc-900 rounded-xl transition duration-200 font-bold text-sm">
                                <i class="fa-solid fa-user-circle me-2"></i> Profile
                            </a>
                            <hr class="my-1 border-slate-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition duration-200 font-bold text-sm">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-zinc-500 font-bold text-xs hover:text-zinc-900 transition-colors">Login</a>
                <a href="{{ route('register') }}" class="btn-premium py-2 px-5 rounded-full text-xs font-bold shadow-sm">Join Free</a>
            @endauth
        </div>

        <!-- Hamburger Button (Mobile Only) -->
        <button id="mobileMenuToggle" class="block md:hidden text-slate-600 hover:text-zinc-900 focus:outline-none p-2 rounded-xl hover:bg-slate-100/50 transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </nav>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileMenu" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div id="mobileMenuBackdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        
        <!-- Drawer Content -->
        <div id="mobileMenuContent" class="fixed top-0 right-0 bottom-0 w-80 max-w-[85vw] bg-white p-6 shadow-2xl flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out">
            <div>
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                    <span class="text-2xl font-black text-zinc-900 tracking-tight">
                        BlogVibe
                    </span>
                    <button id="mobileMenuClose" class="text-slate-400 hover:text-slate-600 focus:outline-none p-2 rounded-xl hover:bg-slate-50 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                
                <!-- Drawer Links -->
                <div class="py-6 flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-slate-700 hover:text-zinc-900 py-2 border-b border-slate-50 transition-colors flex items-center">
                        <i class="fa-solid fa-compass w-8 text-zinc-500"></i> Explore
                    </a>
                    @auth
                        <a href="{{ route('blogs.saved') }}" class="text-lg font-bold text-slate-700 hover:text-zinc-900 py-2 border-b border-slate-50 transition-colors flex items-center">
                            <i class="fa-solid fa-bookmark w-8 text-zinc-500"></i> Saved Reviews
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-zinc-900 py-2 border-b border-slate-50 transition-colors flex items-center">
                                <i class="fa-solid fa-chart-pie w-8 text-zinc-500"></i> Admin Panel
                            </a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="text-lg font-bold text-slate-700 hover:text-zinc-900 py-2 border-b border-slate-50 transition-colors flex items-center">
                            <i class="fa-solid fa-user-circle w-8 text-zinc-500"></i> Profile Settings
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-lg font-bold text-slate-700 hover:text-zinc-900 py-2 border-b border-slate-50 transition-colors flex items-center">
                            <i class="fa-solid fa-right-to-bracket w-8 text-zinc-500"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Drawer Footer / CTA / Logout -->
            <div class="pt-6 border-t border-slate-100">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-500 font-bold py-4 px-6 rounded-2xl transition duration-200">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="btn-premium w-full py-4 text-center">
                        <i class="fa-solid fa-user-plus me-2"></i> Join Free
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 min-h-screen">
        @yield('content')
    </main>

    <footer class="mt-32 border-t border-zinc-200/80 bg-white/70 backdrop-blur-xl relative overflow-hidden">
        <!-- Subtle Top Glow/Line -->
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-zinc-300 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 pt-16 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 mb-16">
                <!-- Column 1: Info -->
                <div class="lg:col-span-4 space-y-6">
                    <a href="{{ route('home') }}" class="text-2xl font-black text-zinc-900 tracking-tight hover:opacity-80 transition-opacity">
                        BlogVibe
                    </a>
                    <p class="text-zinc-500 text-sm leading-relaxed max-w-sm font-medium">
                        Unbiased, expert AI tools reviews and curated guides to supercharge your daily workflow. Discover and save the best products.
                    </p>
                    <div class="flex items-center space-x-3">
                        <a href="#" class="w-10 h-10 rounded-full border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 hover:bg-zinc-50 transition-all shadow-sm">
                            <i class="fa-brands fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 hover:bg-zinc-50 transition-all shadow-sm">
                            <i class="fa-brands fa-github text-sm"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 hover:bg-zinc-50 transition-all shadow-sm">
                            <i class="fa-brands fa-linkedin-in text-sm"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-zinc-900 hover:border-zinc-900 hover:bg-zinc-50 transition-all shadow-sm">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Navigation -->
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="text-zinc-900 text-xs font-bold uppercase tracking-widest">Platform</h4>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">Explore Reviews</a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('blogs.saved') }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">Saved Articles</a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">My Profile</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">Sign In</a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">Create Account</a>
                            </li>
                        @endauth
                    </ul>
                </div>

                <!-- Column 3: Categories -->
                <div class="lg:col-span-2 space-y-4">
                    <h4 class="text-zinc-900 text-xs font-bold uppercase tracking-widest">Categories</h4>
                    <ul class="space-y-2.5">
                        @foreach(['PDF Tools', 'AI Tools', 'Business', 'Comparisons'] as $cat)
                            <li>
                                <a href="{{ route('home') }}?category={{ urlencode($cat) }}" class="text-zinc-500 hover:text-zinc-900 text-sm font-semibold transition-colors">{{ $cat }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div class="lg:col-span-4 space-y-4">
                    <h4 class="text-zinc-900 text-xs font-bold uppercase tracking-widest">Weekly Digest</h4>
                    <p class="text-zinc-500 text-sm leading-relaxed font-medium">
                        Subscribe to get latest tools notifications and curated reviews direct to your inbox.
                    </p>
                    <form id="footerNewsletterForm" class="space-y-3" onsubmit="event.preventDefault(); handleNewsletter();">
                        <div class="relative flex items-center">
                            <input type="email" placeholder="name@domain.com" required
                                class="w-full pl-5 pr-32 py-3.5 rounded-2xl border border-zinc-200 bg-zinc-50/50 focus:bg-white focus:ring-4 focus:ring-zinc-950/5 focus:border-zinc-950 outline-none transition-all text-xs font-semibold text-zinc-700 shadow-inner">
                            <button type="submit" class="absolute right-1.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-sm">
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bottom Line -->
            <div class="border-t border-zinc-100 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-zinc-400 text-xs font-semibold">
                    &copy; {{ date('Y') }} BlogVibe. All rights reserved. Made with <i class="fa-solid fa-heart text-red-500 mx-0.5"></i> for curators.
                </p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-zinc-400 hover:text-zinc-600 text-xs font-semibold transition-colors">Privacy Policy</a>
                    <a href="#" class="text-zinc-400 hover:text-zinc-600 text-xs font-semibold transition-colors">Terms of Service</a>
                    <a href="#" class="text-zinc-400 hover:text-zinc-600 text-xs font-semibold transition-colors">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <script>
        // Newsletter subscription handler
        function handleNewsletter() {
            Swal.fire({
                icon: 'success',
                title: 'Subscribed!',
                text: 'Thank you for subscribing to our weekly newsletter!',
                timer: 3000,
                showConfirmButton: false,
                borderRadius: '24px'
            });
            $('#footerNewsletterForm')[0].reset();
        }
        // Global SweetAlert for Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                borderRadius: '24px'
            });
        @endif

        // AJAX CSRF Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Like Toggle
        function toggleLike(id, btn) {
            $.post(`/blog/${id}/like`, function(res) {
                const icon = $(btn).find('svg');
                const count = $(btn).find('.like-count');
                
                if (res.status === 'liked') {
                    $(btn).addClass('text-red-500').removeClass('text-gray-400');
                    icon.attr('fill', 'currentColor');
                } else {
                    $(btn).addClass('text-gray-400').removeClass('text-red-500');
                    icon.attr('fill', 'none');
                }
                count.text(res.count);
            }).fail(function() {
                window.location.href = "{{ route('login') }}";
            });
        }

        // Save Toggle
        function toggleSave(id, btn) {
            $.post(`/blog/${id}/save`, function(res) {
                if (res.status === 'saved') {
                    $(btn).addClass('text-indigo-600').removeClass('text-gray-400');
                    $(btn).find('svg').attr('fill', 'currentColor');
                } else {
                    $(btn).addClass('text-gray-400').removeClass('text-indigo-600');
                    $(btn).find('svg').attr('fill', 'none');
                }
            }).fail(function() {
                window.location.href = "{{ route('login') }}";
            });
        }

        // Share Blog
        function shareBlog(platform, url, title) {
            const encodedUrl = encodeURIComponent(url);
            const encodedTitle = encodeURIComponent(title);
            let shareUrl = '';

            switch(platform) {
                case 'whatsapp':
                    shareUrl = `https://api.whatsapp.com/send?text=${encodedTitle}%20${encodedUrl}`;
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
                    break;
                case 'insta':
                case 'instagram':
                    // Instagram doesn't have a direct share URL, show alert to copy link
                    Swal.fire({
                        title: 'Share to Instagram',
                        text: 'Instagram doesn\'t support direct link sharing. Copy the link and paste it in your story or bio!',
                        icon: 'info',
                        input: 'text',
                        inputValue: url,
                        confirmButtonText: 'Close',
                        borderRadius: '24px'
                    });
                    return;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }

        // Click to toggle Share Menu dropdown (so that clicking the share icon holds the menu open and supports mobile/touch devices)
        $(document).on('click', '.share-trigger', function(e) {
            e.stopPropagation();
            const parent = $(this).closest('.share-wrap');
            
            // Close other open share menus
            $('.share-wrap').not(parent).removeClass('active');
            
            // Toggle the current menu
            parent.toggleClass('active');
        });

        // Close share menus when clicking anywhere else outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.share-wrap').length) {
                $('.share-wrap').removeClass('active');
            }
        });

        // Mobile Menu Drawer Toggling
        $('#mobileMenuToggle').on('click', function() {
            $('#mobileMenu').removeClass('hidden');
            setTimeout(function() {
                $('#mobileMenuBackdrop').removeClass('opacity-0').addClass('opacity-100');
                $('#mobileMenuContent').removeClass('translate-x-full').addClass('translate-x-0');
            }, 10);
        });

        function closeMobileMenu() {
            $('#mobileMenuBackdrop').removeClass('opacity-100').addClass('opacity-0');
            $('#mobileMenuContent').removeClass('translate-x-0').addClass('translate-x-full');
            setTimeout(function() {
                $('#mobileMenu').addClass('hidden');
            }, 300);
        }

        $('#mobileMenuClose, #mobileMenuBackdrop').on('click', closeMobileMenu);
    </script>
    <script>
</body>
</html>

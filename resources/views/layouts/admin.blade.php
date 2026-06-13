<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 24px; }
        .sidebar-link { transition: all 0.3s ease; border-radius: 16px; margin: 4px 12px; padding: 12px 20px; display: flex; align-items: center; font-weight: 600; color: #71717a; }
        .sidebar-link i { font-size: 1.25rem; margin-right: 12px; width: 24px; text-align: center; }
        .sidebar-link:hover { background: #f4f4f5; color: #18181b; }
        .sidebar-link.active { background: #18181b; color: white; box-shadow: 0 4px 12px rgba(24, 24, 27, 0.1); }
        .fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .btn-premium { background: #18181b; color: white; font-weight: 700; border-radius: 16px; border: 1px solid #18181b; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; }
        .btn-premium:hover { transform: translateY(-2px); background: #27272a; border-color: #27272a; box-shadow: 0 10px 20px rgba(24, 24, 27, 0.15); opacity: 0.95; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-slate-900 overflow-hidden">
    <div class="flex h-screen relative">
        <!-- Sidebar Backdrop (Mobile Only) -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/40 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

        <!-- Sidebar -->
        <aside id="adminSidebar" class="fixed lg:relative inset-y-0 left-0 w-72 bg-white border-r border-slate-100 flex flex-col h-full z-50 shadow-sm transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-8 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-extrabold text-zinc-900 tracking-tight">
                    BlogVibe <span class="text-xs font-black bg-zinc-100 text-zinc-800 px-2 py-1 rounded-lg ml-1 uppercase">Admin</span>
                </a>
                <button id="closeSidebar" class="lg:hidden text-slate-400 hover:text-indigo-600 focus:outline-none p-2 rounded-xl hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="{{ route('admin.blogs') }}" class="sidebar-link {{ request()->routeIs('admin.blogs*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> Manage Blogs
                </a>
                <div class="my-4 mx-6 border-t border-slate-100"></div>
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="fa-solid fa-compass"></i> Explore Site
                </a>
                <a href="{{ route('blogs.saved') }}" class="sidebar-link">
                    <i class="fa-solid fa-bookmark"></i> Saved Posts
                </a>
            </nav>

            <div class="p-6 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-5 py-4 text-red-500 hover:bg-red-50 rounded-2xl transition font-bold">
                        <i class="fa-solid fa-right-from-bracket mr-3"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
            <!-- Top Header -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 md:px-8 flex items-center justify-between z-40">
                <div class="flex items-center">
                    <button id="toggleSidebar" class="lg:hidden text-slate-500 hover:text-indigo-600 focus:outline-none p-2 rounded-xl hover:bg-slate-100 transition-colors mr-3">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg md:text-xl font-bold text-slate-800">@yield('header')</h2>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="relative group">
                        <button class="flex items-center space-x-3 focus:outline-none">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-slate-500 font-medium">Administrator</div>
                            </div>
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-10 h-10 rounded-full border-2 border-zinc-900 object-cover shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=18181b&color=fff" class="w-10 h-10 rounded-full border-2 border-zinc-900 shadow-sm">
                            @endif
                        </button>
                        
                        <!-- Profile Hover Dropdown -->
                        <div class="absolute right-0 w-48 mt-0 pt-2 hidden group-hover:block fade-in z-[60]">
                            <div class="glass shadow-2xl border border-white/20 rounded-2xl p-2 overflow-hidden bg-white">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-slate-700 hover:bg-zinc-50 hover:text-zinc-900 rounded-xl transition duration-200 font-bold text-sm">
                                    <i class="fa-solid fa-user-circle me-2"></i> My Profile
                                </a>
                                <hr class="my-1 border-slate-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition duration-200 font-bold text-sm">
                                        <i class="fa-solid fa-power-off me-2"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    
    <script>
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

        // Sidebar Toggling for Mobile Viewports
        $('#toggleSidebar').on('click', function() {
            $('#sidebarBackdrop').removeClass('hidden');
            $('#adminSidebar').removeClass('-translate-x-full');
            setTimeout(function() {
                $('#sidebarBackdrop').removeClass('opacity-0').addClass('opacity-100');
            }, 10);
        });

        function closeSidebar() {
            $('#sidebarBackdrop').removeClass('opacity-100').addClass('opacity-0');
            $('#adminSidebar').addClass('-translate-x-full');
            setTimeout(function() {
                $('#sidebarBackdrop').addClass('hidden');
            }, 300);
        }

        $('#closeSidebar, #sidebarBackdrop').on('click', closeSidebar);
    </script>
</body>
</html>

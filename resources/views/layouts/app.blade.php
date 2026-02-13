<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory ERP')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 (Keep for compatibility) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); }
        
        /* Bootstrap Override for Cards */
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); }
        .card-header { background: white; border-bottom: 1px solid #f1f5f9; padding: 1.25rem; border-radius: 16px 16px 0 0 !important; }
        .table > :not(caption) > * > * { padding: 1rem 1rem; border-bottom-color: #f1f5f9; }
        .btn { border-radius: 8px; padding: 0.5rem 1rem; font-weight: 500; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased overflow-hidden">
    
    <div class="flex h-screen overflow-hidden relative">
        <!-- Mobile Backdrop -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
            
            <!-- Topbar -->
            <header class="h-[70px] bg-white/80 backdrop-blur-md shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border-b border-gray-100 flex items-center justify-between px-6 shrink-0 z-40 sticky top-0">
                <!-- Left: Mobile Toggle & Title -->
                <div class="flex items-center gap-4">
                    <button class="lg:hidden text-gray-500 hover:text-emerald-600 transition-colors" onclick="toggleSidebar()">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-heading font-bold text-gray-800 tracking-tight hidden md:block">@yield('title', 'Dashboard')</h1>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-4">
                    <!-- Date Display -->
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ now()->format('l, d F Y') }}</span>
                    </div>


                    <!-- User Dropdown (Simple) -->
                    <div class="h-8 w-[1px] bg-gray-200 mx-1"></div>
                    
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-700 leading-none">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-emerald-600 font-medium uppercase tracking-wider mt-0.5">Online</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=10b981&color=ffffff" alt="" class="w-9 h-9 rounded-full border-2 border-emerald-100 shadow-sm">
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-[#f8fafc] p-4 lg:p-6 relative scroll-smooth">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarMenu');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                backdrop.classList.add('hidden');
            }
        }

        // Close sidebar when resizing to large screen
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebarBackdrop').classList.add('hidden');
                document.getElementById('sidebarMenu').classList.add('hidden'); // Reset to default state logic if needed, but 'hidden lg:flex' handles visibility. 
                // Actually, if we remove 'hidden', it shows. On LG it shows anyway.
                // But we should ensure backdrop is hidden.
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị hệ thống') - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}</title>
    

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('admin_theme') === 'dark' || (!('admin_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-zinc-100 font-sans antialiased min-h-screen flex transition-colors duration-200">

    <!-- Collapsible Sidebar -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-zinc-900 border-r border-slate-200 dark:border-zinc-800 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
        <!-- Logo -->
        <div class="h-16 border-b border-slate-200 dark:border-zinc-800 flex items-center px-6 gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-sm">
                C
            </div>
            <span class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-white uppercase">
                {{ \App\Models\SystemSetting::getValue('site_name', 'Converter') }}
            </span>
        </div>

        <!-- User profile -->
        <div class="p-4 border-b border-slate-100 dark:border-zinc-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-600/10 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm border border-indigo-200 dark:border-indigo-900">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs font-bold text-slate-900 dark:text-white block truncate">{{ auth()->user()->name }}</span>
                <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">
                    {{ auth()->user()->role ? auth()->user()->role->name : 'Staff' }}
                </span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto text-xs font-bold uppercase tracking-wider">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                Tổng Quan
            </a>

            @if(auth()->user()->hasPermission('users', 'read'))
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.users*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A2.25 2.25 0 0112.75 21.5h-1.5a2.25 2.25 0 01-2.25-2.263V19.13m-4.75-3.07a9.3 9.3 0 01-.786 3.07M11.25 21.5v-6A2.25 2.25 0 009 13.25H6.75M12.75 21.5v-6A2.25 2.25 0 0115 13.25h2.25M6.75 13.25a4.5 4.5 0 01-3-1.688M3.75 11.562a9.049 9.049 0 014.12-1.59M6.75 13.25H3.75M9 13.25V9.75M12 9.75v3.5m0-3.5h3.5m-3.5 0V6a2.25 2.25 0 00-2.25-2.25h-1.5A2.25 2.25 0 006 6v3.75m6 0h2.25M17.25 13.25a4.5 4.5 0 003-1.688M20.25 11.562a9.049 9.049 0 00-4.12-1.59M17.25 13.25H20.25m-3 0V9.75m3-3.75V6a2.25 2.25 0 00-2.25-2.25h-1.5A2.25 2.25 0 0012 6v3.75" />
                    </svg>
                    Người Dùng
                </a>
            @endif

            @if(auth()->user()->hasPermission('conversions', 'read'))
                <a href="{{ route('admin.conversions') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.conversions*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Nhật Ký Convert
                </a>
                
                <a href="{{ route('admin.posts') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.posts*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Bài Viết SEO
                </a>
                
                <a href="{{ route('admin.blog-categories') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.blog-categories*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                    </svg>
                    Danh Mục SEO
                </a>
            @endif

            @if(auth()->user()->hasPermission('settings', 'read'))
                <hr class="border-slate-100 dark:border-zinc-800 my-4">

                <a href="{{ route('admin.roles') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.roles*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-5.7L18.75 6.5a2.25 2.25 0 011.5 2.25v6.75a2.25 2.25 0 01-1.5 2.25L12 20.25a2.25 2.25 0 01-3 0L3.75 17.25a2.25 2.25 0 01-1.5-2.25V8.75a2.25 2.25 0 011.5-2.25L12 4.05z" />
                    </svg>
                    Phân Quyền Vai Trò
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ Route::is('admin.settings*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10' : 'text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800/40 hover:text-slate-900 dark:hover:text-zinc-200' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.645-.869l.214-1.28z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Cài Đặt Hệ Thống
                </a>
            @endif
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-200 dark:border-zinc-800 flex items-center justify-between bg-slate-50/50 dark:bg-zinc-950/20">
            <button onclick="toggleAdminTheme()" class="w-10 h-10 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                <svg class="w-4 h-4 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-zinc-400 hover:text-red-500 dark:hover:text-red-400 transition-colors uppercase tracking-wider">
                    Đăng Xuất &rarr;
                </button>
            </form>
        </div>
    </aside>

    <!-- Content Workspace -->
    <main class="flex-1 min-w-0 lg:pl-64 flex flex-col min-h-screen">
        <!-- Top Nav -->
        <header class="h-16 bg-white dark:bg-zinc-900 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between px-6 sm:px-8 sticky top-0 z-30">
            <button onclick="toggleMobileSidebar()" class="lg:hidden text-slate-500 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white mr-4 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Breadcrumbs -->
            <nav class="hidden sm:flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-zinc-400 uppercase tracking-widest">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Quản trị</a>
                <svg class="w-3 h-3 text-slate-300 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-slate-900 dark:text-white">@yield('page-title', 'Tổng Quan')</span>
            </nav>

            <div class="flex items-center gap-4">
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-600 uppercase tracking-wider hidden md:block">
                    Hệ thống hoạt động ổn định | {{ now()->format('d M H:i') }}
                </span>
            </div>
        </header>

        <!-- View Content Area -->
        <div class="flex-grow p-6 sm:p-8 space-y-8">
            @if(session('success'))
                <div id="flash-success" class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/60 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-2xl p-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-900 dark:hover:text-white transition-colors">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div id="flash-error" class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 text-xs font-semibold rounded-2xl p-4 flex items-center justify-between shadow-sm">
                    <div class="flex flex-col gap-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                    <button onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-900 dark:hover:text-white transition-colors">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="h-14 border-t border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/40 text-slate-400 dark:text-zinc-600 text-[10px] font-bold uppercase tracking-widest flex items-center px-8 justify-between">
            <span>Bảng quản trị {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}</span>
            <span>&copy; {{ date('Y') }}</span>
        </footer>
    </main>

    <!-- Mobile Sidebar overlay -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 hidden lg:hidden"></div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function toggleAdminTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('admin_theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('admin_theme', 'dark');
            }
        }
    </script>

</body>
</html>

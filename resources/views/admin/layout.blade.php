<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - Universal Image Converter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Left Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-zinc-900 border-r border-zinc-800 flex flex-col shrink-0">
        <!-- Logo Header -->
        <div class="h-16 border-b border-zinc-800 flex items-center px-6 gap-2">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <span class="text-md font-bold text-white tracking-tight">Image <span class="text-zinc-500">Converter</span></span>
        </div>

        <!-- User Profile Quick Card -->
        <div class="p-4 border-b border-zinc-850 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-zinc-800 border border-zinc-700 flex items-center justify-center font-bold text-sm text-zinc-300">
                A
            </div>
            <div>
                <span class="text-xs font-bold text-white block">Administrator</span>
                <span class="text-[10px] font-semibold text-zinc-500 block">Session Active</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-grow p-4 space-y-1 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ Route::is('admin.dashboard') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:bg-zinc-800/40 hover:text-zinc-200' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ Route::is('admin.users') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:bg-zinc-800/40 hover:text-zinc-200' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users List
            </a>

            <a href="{{ route('admin.logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ Route::is('admin.logs') ? 'bg-zinc-800 text-white' : 'text-zinc-400 hover:bg-zinc-800/40 hover:text-zinc-200' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Conversion Logs
            </a>

            <hr class="border-zinc-850 my-4">

            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-zinc-500 hover:text-zinc-300 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Open Homepage
            </a>
        </nav>

        <!-- Sidebar Footer/Logout -->
        <div class="p-4 border-t border-zinc-800 bg-zinc-950/20">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs font-bold text-zinc-400 hover:text-white bg-zinc-800 hover:bg-zinc-700 py-2.5 px-4 rounded-lg transition-colors uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Workspace -->
    <main class="flex-grow min-w-0 flex flex-col min-h-screen">
        <!-- Top Toolbar Header -->
        <header class="h-16 border-b border-zinc-800 bg-zinc-900/10 flex items-center px-8 justify-between">
            <h2 class="text-sm font-bold text-white uppercase tracking-widest">@yield('page-title', 'Dashboard')</h2>
            
            <div class="text-[11px] text-zinc-500 font-semibold uppercase tracking-widest hidden sm:block">
                Server Time: {{ now()->format('H:i') }}
            </div>
        </header>

        <!-- Main View Content Area -->
        <div class="flex-grow p-8 space-y-8">
            
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div id="alert-banner" class="bg-emerald-950/30 border border-emerald-900/60 text-emerald-400 text-xs rounded-xl p-4 flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('alert-banner').remove()" class="text-emerald-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @yield('content')

        </div>

        <!-- Footer -->
        <footer class="border-t border-zinc-900 bg-zinc-950/40 text-zinc-600 text-[10px] font-semibold uppercase tracking-widest py-4 px-8 text-center sm:text-left">
            <span>Universal Image Converter Admin Panel</span>
        </footer>
    </main>

</body>
</html>

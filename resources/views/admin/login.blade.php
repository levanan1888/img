<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Universal Image Converter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans antialiased min-h-screen flex items-center justify-center bg-grid-dark relative">

    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(120,119,198,0.15),rgba(255,255,255,0))]"></div>

    <div class="w-full max-w-md px-6 py-12 relative z-10 space-y-8">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-zinc-900 border border-zinc-800 text-white shadow-inner mb-2">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-white uppercase">Control Center</h1>
            <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest">Sign in to manage system platforms</p>
        </div>

        <!-- Login Card -->
        <div class="bg-zinc-900 border border-zinc-850 rounded-2xl p-8 shadow-xl">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                @if($errors->has('login_error'))
                    <div class="bg-red-950/50 border border-red-900 text-red-400 text-xs rounded-lg p-3.5 flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first('login_error') }}</span>
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="username" class="text-xs font-semibold text-zinc-400 uppercase tracking-wider block">Username or Email</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required class="w-full bg-zinc-950/60 border border-zinc-800 focus:border-zinc-700 text-zinc-100 text-sm font-medium rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-700 transition-colors" placeholder="admin or email">
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-xs font-semibold text-zinc-400 uppercase tracking-wider block">Password</label>
                    <input type="password" name="password" id="password" required class="w-full bg-zinc-950/60 border border-zinc-800 focus:border-zinc-700 text-zinc-100 text-sm font-medium rounded-lg px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-zinc-700 transition-colors" placeholder="Enter password">
                </div>

                <div class="text-[10px] text-zinc-500 font-semibold bg-zinc-950/40 border border-zinc-800/60 rounded-md p-3 space-y-1.5 leading-relaxed">
                    <span class="text-zinc-400 block mb-0.5 uppercase tracking-wider">Authentication Methods:</span>
                    <div>• Database (Seeded): <span class="text-zinc-300 font-mono select-all">admin@example.com</span> / <span class="text-zinc-300 font-mono select-all">admin123</span></div>
                    <div>• Environment (.env): <span class="text-zinc-300 font-mono select-all">admin</span> / <span class="text-zinc-300 font-mono select-all">admin</span></div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-black bg-white hover:bg-zinc-200 active:bg-zinc-300 transition-colors py-3 px-6 rounded-lg uppercase tracking-wider shadow-sm">
                    Authenticate
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="text-[10px] font-bold text-zinc-500 hover:text-zinc-300 transition-colors uppercase tracking-widest">
                &larr; Back to home
            </a>
        </div>
    </div>

</body>
</html>

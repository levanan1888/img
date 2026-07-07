<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập hệ thống - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}</title>
    

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        if (localStorage.getItem('admin_theme') === 'dark' || (!('admin_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-zinc-100 font-sans antialiased min-h-screen flex items-center justify-center relative transition-colors duration-200">

    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.06),rgba(255,255,255,0))] dark:bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(99,102,241,0.12),rgba(255,255,255,0))]"></div>

    <div class="w-full max-w-md px-6 py-12 relative z-10 space-y-8">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-indigo-600 dark:text-indigo-400 shadow-sm mb-2">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m-3.75 3h15.75c.621 0 1.125.504 1.125 1.125v1.75c0 .621-.504 1.125-1.125 1.125H3.75c-.621 0-1.125-.504-1.125-1.125v-1.75c0-.621.504-1.125 1.125-1.125z" />
                </svg>
            </div>
            <h1 class="text-xl font-extrabold tracking-tight uppercase">Cổng Quản Trị</h1>
            <p class="text-xs font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest">Đăng nhập để quản lý cấu hình hệ thống</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-8 shadow-xl">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                @if($errors->has('login_error'))
                    <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 text-xs font-semibold rounded-xl p-3.5 flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first('login_error') }}</span>
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider block">Địa chỉ Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-sm rounded-xl px-4 py-2.5 focus:outline-none transition-colors" placeholder="admin@example.com">
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider block">Mật khẩu</label>
                    </div>
                    <input type="password" name="password" id="password" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-sm rounded-xl px-4 py-2.5 focus:outline-none transition-colors" placeholder="Nhập mật khẩu">
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 dark:border-zinc-800 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="ml-2 block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <div class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold bg-slate-50 dark:bg-zinc-950/40 border border-slate-100 dark:border-zinc-850 rounded-xl p-3.5 space-y-1.5 leading-relaxed uppercase tracking-wider">
                    <span class="text-slate-500 dark:text-zinc-400 block mb-0.5">Tài khoản Quản Trị mẫu:</span>
                    <div>• Admin: <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">admin@example.com</span> / <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">admin123</span></div>
                    <div>• Manager: <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">manager@example.com</span> / <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">admin123</span></div>
                    <div>• Staff: <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">staff@example.com</span> / <span class="text-slate-800 dark:text-zinc-300 select-all font-mono lowercase">admin123</span></div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 transition-colors py-3 px-6 rounded-xl uppercase tracking-widest shadow-md shadow-indigo-600/10">
                    Xác thực đăng nhập
                </button>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors uppercase tracking-widest">
                &larr; Quay lại trang chủ
            </a>
        </div>
    </div>

</body>
</html>

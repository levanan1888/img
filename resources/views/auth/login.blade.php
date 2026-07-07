<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 text-zinc-900 font-sans antialiased min-h-screen flex items-center justify-center relative">

    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(9,9,11,0.02),rgba(255,255,255,0))]"></div>

    <div class="w-full max-w-md px-6 py-12 relative z-10 space-y-6">
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-black text-lg text-zinc-900 focus:outline-none mb-2">
                <svg class="w-6 h-6 text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span>Image <span class="text-zinc-500">Converter</span></span>
            </a>
            <h1 class="text-xl font-bold tracking-tight text-zinc-900">Chào mừng trở lại</h1>
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Đăng nhập tài khoản thành viên của bạn</p>
        </div>

        <div class="bg-white border border-zinc-200/80 rounded-2xl p-8 shadow-sm">
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                @if($errors->has('login_error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-xl p-3.5 flex items-start gap-2.5">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ $errors->first('login_error') }}</span>
                    </div>
                @endif

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Địa chỉ Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full bg-zinc-50 border border-zinc-200 focus:border-zinc-900 text-zinc-900 text-xs rounded-xl px-4 py-2.5 focus:outline-none transition-colors" placeholder="user@example.com">
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Mật khẩu</label>
                    <input type="password" name="password" id="password" required class="w-full bg-zinc-50 border border-zinc-200 focus:border-zinc-900 text-zinc-900 text-xs rounded-xl px-4 py-2.5 focus:outline-none transition-colors" placeholder="Nhập mật khẩu">
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                    <label for="remember" class="ml-2 block text-xs font-bold text-zinc-500 uppercase tracking-wider">
                        Ghi nhớ thiết bị
                    </label>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-white bg-zinc-900 hover:bg-zinc-800 active:bg-black transition-colors py-3 px-6 rounded-xl uppercase tracking-widest shadow-sm">
                    Đăng nhập
                </button>
            </form>
        </div>

        <div class="text-center text-xs font-semibold text-zinc-500">
            Chưa có tài khoản? 
            <a href="{{ route('register') }}" class="text-zinc-900 hover:underline">Đăng ký ngay &rarr;</a>
        </div>
    </div>

</body>
</html>

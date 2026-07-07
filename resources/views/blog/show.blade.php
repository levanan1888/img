<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->seo_title ?: $post->title }} - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}</title>
    
    @if($post->seo_description)
        <meta name="description" content="{{ $post->seo_description }}">
    @endif
    @if($post->seo_keywords)
        <meta name="keywords" content="{{ $post->seo_keywords }}">
    @endif
    
    <!-- Open Graph tags for social share SEO -->
    <meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->seo_description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($post->image_path)
        <meta property="og:image" content="{{ asset($post->image_path) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-zinc-900 font-sans antialiased min-h-screen flex flex-col bg-grid-light">

    <!-- Sticky Navigation Header -->
    <header class="sticky top-0 z-50 w-full border-b border-zinc-200/50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 h-14 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2 focus:outline-none focus:ring-1 focus:ring-zinc-400 rounded-md p-1">
                    <svg class="w-6 h-6 text-zinc-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="text-sm font-bold text-zinc-900 tracking-tight">Image <span class="text-zinc-500">Converter</span></span>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors uppercase tracking-wider">Trang Chủ</a>
                <a href="{{ route('blog') }}" class="text-xs font-bold text-zinc-900 transition-colors uppercase tracking-wider">Blog</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <span class="text-xs font-bold text-zinc-700">Xin chào, {{ auth()->user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors py-1.5 px-2.5 uppercase tracking-wider">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Reader -->
    <main class="flex-grow max-w-4xl w-full mx-auto px-6 sm:px-8 py-12 space-y-8">
        
        <!-- Breadcrumb link (SEO Silo Architecture) -->
        <nav class="flex items-center gap-2 text-[10px] font-bold text-zinc-400 uppercase tracking-widest" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-zinc-950">Trang chủ</a>
            <span>&rarr;</span>
            <a href="{{ route('blog') }}" class="hover:text-zinc-950">Blog</a>
            @if($post->category)
                <span>&rarr;</span>
                <a href="{{ route('blog.category', $post->category->slug) }}" class="hover:text-zinc-950 text-indigo-650">{{ $post->category->name }}</a>
            @endif
        </nav>

        <!-- Header Info -->
        <div class="space-y-4">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-zinc-950 tracking-tight leading-tight">
                {{ $post->title }}
            </h1>
            
            <div class="flex items-center gap-3 text-xs text-zinc-400 font-semibold uppercase tracking-wider">
                <span>Đăng ngày: {{ $post->created_at->format('d/m/Y') }}</span>
                <span>&middot;</span>
                <span>Người viết: {{ $post->author ? $post->author->name : 'Quản trị viên' }}</span>
            </div>
        </div>

        <!-- Featured Image -->
        @if($post->image_path)
            <div class="w-full aspect-video rounded-3xl overflow-hidden border border-zinc-200 shadow-sm bg-zinc-50 flex items-center justify-center">
                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Excerpt highlight -->
        @if($post->summary)
            <div class="bg-zinc-50 border-l-4 border-zinc-900 p-5 rounded-r-2xl text-sm font-medium text-zinc-650 leading-relaxed italic">
                {{ $post->summary }}
            </div>
        @endif

        <!-- Body Markdown/HTML content -->
        <article class="prose max-w-none text-zinc-800 text-sm leading-relaxed space-y-6">
            {!! nl2br(e($post->content)) !!}
        </article>

    </main>

    <!-- Footer -->
    <footer class="bg-zinc-950 text-zinc-400 text-xs border-t border-zinc-800 py-8 mt-20">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 text-center sm:text-left sm:flex sm:items-center sm:justify-between">
            <span class="text-[11px] font-semibold text-zinc-500">© 2026 Universal Image Converter. Bản quyền đã được bảo hộ.</span>
            <div class="flex items-center justify-center gap-4 mt-2 sm:mt-0 font-semibold text-zinc-400">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Công Cụ Convert</a>
                <span>&middot;</span>
                <a href="{{ route('blog') }}" class="hover:text-white transition-colors">Blog</a>
            </div>
        </div>
    </footer>

</body>
</html>

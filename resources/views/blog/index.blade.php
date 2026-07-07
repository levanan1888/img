<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if($currentCategory)
            Chuyên mục: {{ $currentCategory->name }} - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}
        @else
            Blog - {{ \App\Models\SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh') }}
        @endif
    </title>
    <meta name="description" content="Chuyên trang chia sẻ kiến thức, hướng dẫn chuyển đổi định dạng ảnh, tối ưu hóa dung lượng ảnh hoàn toàn miễn phí.">
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
                    @if(auth()->user()->role)
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-850 uppercase tracking-wider">Quản trị</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900 transition-colors py-1.5 px-2.5 uppercase tracking-wider">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-6 sm:px-8 lg:px-12 py-12">
        <div class="max-w-2xl mb-8">
            <h1 class="text-3xl font-extrabold text-zinc-950 tracking-tight mb-2">
                @if($currentCategory)
                    Chuyên Mục: {{ $currentCategory->name }}
                @else
                    Cẩm Nang Hướng Dẫn
                @endif
            </h1>
            <p class="text-sm text-zinc-500">
                @if($currentCategory && $currentCategory->description)
                    {{ $currentCategory->description }}
                @else
                    Tìm kiếm các hướng dẫn thủ thuật xử lý ảnh, tối ưu hóa SEO và kiến thức định dạng tệp tin từ chuyên gia.
                @endif
            </p>
        </div>

        <!-- Horizontal Categories Filter Bar -->
        <div class="flex flex-wrap items-center gap-2 mb-10 pb-4 border-b border-zinc-100">
            <a href="{{ route('blog') }}" class="text-xs font-bold px-4 py-2 rounded-xl transition-all uppercase tracking-wider border {{ !$currentCategory ? 'bg-zinc-950 border-zinc-950 text-white shadow-sm' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                Tất cả blog
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.category', $cat->slug) }}" class="text-xs font-bold px-4 py-2 rounded-xl transition-all uppercase tracking-wider border {{ $currentCategory && $currentCategory->id === $cat->id ? 'bg-zinc-950 border-zinc-950 text-white shadow-sm' : 'bg-white border-zinc-200 text-zinc-650 hover:bg-zinc-50' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white border border-zinc-200/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div>
                        <!-- Thumbnail -->
                        <div class="aspect-video w-full bg-zinc-50 border-b border-zinc-150 overflow-hidden flex items-center justify-center">
                            @if($post->image_path)
                                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-10 h-10 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-3">
                            @if($post->category)
                                <span class="text-[9px] font-bold text-indigo-650 bg-indigo-50 border border-indigo-100 rounded px-2.5 py-0.5 uppercase tracking-wider block w-max">{{ $post->category->name }}</span>
                            @else
                                <span class="text-[9px] font-bold text-zinc-500 bg-zinc-50 border border-zinc-100 rounded px-2.5 py-0.5 uppercase tracking-wider block w-max">Tổng hợp</span>
                            @endif
                            
                            <h2 class="text-sm font-bold text-zinc-950 hover:text-indigo-600 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-xs text-zinc-500 line-clamp-3 leading-relaxed font-normal">{{ $post->summary }}</p>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 pb-6 pt-3 flex items-center justify-between border-t border-zinc-100 text-[10px] font-semibold text-zinc-400">
                        <span>{{ $post->created_at->format('Y-m-d') }}</span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-zinc-900 hover:text-indigo-650 font-bold uppercase tracking-wider flex items-center gap-1">
                            Đọc tiếp &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-zinc-400 font-semibold text-sm">
                    Chưa có blog nào trong chuyên mục này.
                </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="mt-12 pt-6 border-t border-zinc-100">
                {{ $posts->links() }}
            </div>
        @endif
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

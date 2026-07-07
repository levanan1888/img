@extends('admin.layouts.app')

@section('title', 'Quản Lý Bài Viết SEO')
@section('page-title', 'Bài Viết SEO')

@section('content')

    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <!-- Search & Filter Form -->
        <form action="{{ route('admin.posts') }}" method="GET" class="flex-grow flex flex-wrap items-center gap-3">
            <div class="relative flex-grow max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl pl-9 pr-4 py-2.5 focus:outline-none transition-colors" placeholder="Tìm kiếm theo tiêu đề bài viết...">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <select name="status" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Tất Cả Trạng Thái</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.posts') }}" class="text-xs font-bold text-rose-500 hover:text-rose-600 uppercase tracking-wider py-2">Xóa Bộ Lọc</a>
            @endif
        </form>

        <!-- Write Post Button -->
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-indigo-600/10">
            Viết Bài Mới
        </a>
    </div>

    <!-- Data Grid Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-200 dark:border-zinc-800">
                    <tr>
                        <th class="py-4 px-6 w-20">Ảnh bìa</th>
                        <th class="py-4 px-6">Tiêu Đề</th>
                        <th class="py-4 px-6">Người Viết</th>
                        <th class="py-4 px-6">Trạng Thái</th>
                        <th class="py-4 px-6">Ngày Tạo</th>
                        <th class="py-4 px-6 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/25 transition-colors">
                            <td class="py-3.5 px-6">
                                <div class="w-12 h-10 rounded-lg bg-slate-100 dark:bg-zinc-850 border border-slate-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($post->image_path)
                                        <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $post->title }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono leading-none" title="{{ $post->slug }}">{{ $post->slug }}</span>
                                    @if($post->category)
                                        <span class="text-[8px] font-bold text-indigo-650 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/60 px-1.5 py-0.5 rounded uppercase tracking-wider">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-6 font-semibold">{{ $post->author ? $post->author->name : 'N/A' }}</td>
                            <td class="py-3.5 px-6">
                                @if($post->status === 'published')
                                    <span class="inline-flex items-center text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-md uppercase tracking-wider">Đã Xuất Bản</span>
                                @else
                                    <span class="inline-flex items-center text-[9px] font-bold text-slate-500 bg-slate-100 dark:bg-zinc-800 px-2 py-0.5 rounded-md uppercase tracking-wider">Bản Nháp</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 dark:text-zinc-550">{{ $post->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-3.5 px-6 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-850 font-bold transition-colors">Sửa</a>
                                <span>&middot;</span>
                                <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn muốn xóa bài viết này vĩnh viễn?');">
                                    @csrf
                                    <button type="submit" class="text-rose-500 hover:text-rose-750 font-bold transition-colors">Xóa</button>
                                </form>
                                @if($post->status === 'published')
                                    <span>&middot;</span>
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold transition-colors">Xem client</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-zinc-500 font-semibold">Chưa có bài viết nào được đăng tải.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

@endsection

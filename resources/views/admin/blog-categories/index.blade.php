@extends('admin.layouts.app')

@section('title', 'Quản Lý Danh Mục SEO')
@section('page-title', 'Danh Mục SEO')

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Add Category Form (1/3 width) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm h-fit">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3 mb-4">Thêm Danh Mục Mới</h3>
            
            <form action="{{ route('admin.blog-categories.create') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label for="name" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tên Danh Mục</label>
                    <input type="text" name="name" id="name" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="e.g. Thủ thuật convert ảnh">
                </div>

                <div class="space-y-1">
                    <label for="description" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mô tả ngắn</label>
                    <textarea name="description" id="description" rows="3" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="Nhập mô tả ngắn về danh mục này..."></textarea>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl uppercase tracking-widest shadow-md shadow-indigo-600/10 transition-colors">
                    Lưu Danh Mục
                </button>
            </form>
        </div>

        <!-- Right Side: Categories List Table (2/3 width) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between lg:col-span-2">
            <div class="p-6 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Danh Sách Danh Mục</h3>
                <form action="{{ route('admin.blog-categories') }}" method="GET" class="relative max-w-xs">
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-850 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-[11px] rounded-xl pl-8 pr-3 py-1.5 focus:outline-none transition-colors" placeholder="Tìm danh mục...">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                    <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-200 dark:border-zinc-800">
                        <tr>
                            <th class="py-4 px-6">Tên Danh Mục</th>
                            <th class="py-4 px-6">Đường Dẫn Slug</th>
                            <th class="py-4 px-6 text-center">Số Bài Viết</th>
                            <th class="py-4 px-6 text-right">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60 font-medium">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/25 transition-colors">
                                <td class="py-3.5 px-6">
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $cat->name }}</span>
                                    @if($cat->description)
                                        <span class="text-[10px] text-slate-400 dark:text-zinc-500 block leading-relaxed mt-0.5">{{ $cat->description }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-500 dark:text-zinc-450">{{ $cat->slug }}</td>
                                <td class="py-3.5 px-6 text-center font-bold text-slate-900 dark:text-white">{{ $cat->posts_count }}</td>
                                <td class="py-3.5 px-6 text-right space-x-1.5 whitespace-nowrap">
                                    <button onclick="toggleEditDrawer('{{ $cat->id }}')" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-850 font-bold transition-colors">Sửa</button>
                                    <span>&middot;</span>
                                    <form action="{{ route('admin.blog-categories.delete', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn muốn xóa danh mục này?');">
                                        @csrf
                                        <button type="submit" class="text-rose-500 hover:text-rose-750 font-bold transition-colors">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 dark:text-zinc-500 font-semibold">Chưa có danh mục nào được khởi tạo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Category drawers -->
    @foreach($categories as $cat)
        <div id="edit-drawer-{{ $cat->id }}" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out hidden flex-col">
            <div class="h-16 border-b border-slate-200 dark:border-zinc-800 px-6 flex items-center justify-between bg-slate-50 dark:bg-zinc-950/40">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Sửa Danh Mục</h4>
                <button onclick="toggleEditDrawer('{{ $cat->id }}')" class="text-slate-500 hover:text-slate-950 dark:hover:text-white">Đóng</button>
            </div>
            <form action="{{ route('admin.blog-categories.update', $cat->id) }}" method="POST" class="flex-grow flex flex-col justify-between">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tên Danh Mục</label>
                        <input type="text" name="name" value="{{ $cat->name }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mô Tả</label>
                        <textarea name="description" rows="3" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">{{ $cat->description }}</textarea>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/40">
                    <button type="submit" class="w-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl uppercase tracking-widest shadow-md shadow-indigo-600/10">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
        <div id="backdrop-{{ $cat->id }}" class="fixed inset-0 bg-slate-950/30 backdrop-blur-sm z-40 hidden" onclick="toggleEditDrawer('{{ $cat->id }}')"></div>
    @endforeach

    <script>
        function toggleEditDrawer(id) {
            const drawer = document.getElementById(`edit-drawer-${id}`);
            const backdrop = document.getElementById(`backdrop-${id}`);
            if (drawer.classList.contains('hidden')) {
                drawer.classList.remove('hidden');
                backdrop.classList.remove('hidden');
                setTimeout(() => drawer.classList.remove('translate-x-full'), 10);
            } else {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
                setTimeout(() => drawer.classList.add('hidden'), 300);
            }
        }
    </script>

@endsection

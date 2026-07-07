@extends('admin.layouts.app')

@section('title', 'Soạn Thảo Bài Viết Mới')
@section('page-title', 'Viết Bài Mới')

@section('content')

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Main write workspace (2/3 width) -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Specifications Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Nội Dung Bài Viết</h3>
                
                <div class="space-y-1">
                    <label for="title" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tiêu Đề Bài Viết</label>
                    <input type="text" name="title" id="title" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="e.g. Hướng dẫn convert ảnh JPG sang PNG chất lượng cao">
                </div>

                <div class="space-y-1">
                    <label for="category_id" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Danh Mục Bài Viết</label>
                    <select name="category_id" id="category_id" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="">-- Chọn danh mục (Bắt buộc cho cấu trúc SEO) --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="summary" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mô Tả Ngắn (Excerpt)</label>
                    <textarea name="summary" id="summary" rows="3" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="Viết tóm tắt nội dung bài viết hiển thị ở trang danh sách bài viết..."></textarea>
                </div>

                <div class="space-y-1">
                    <label for="content" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Nội Dung Chi Tiết (Markdown/HTML)</label>
                    <textarea name="content" id="content" rows="12" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none font-mono" placeholder="Nhập nội dung bài viết chi tiết tại đây..."></textarea>
                </div>
            </div>

            <!-- SEO Accordion Config -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
                <button type="button" onclick="toggleSeoAccordion()" class="w-full flex items-center justify-between focus:outline-none">
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Tối Ưu Hóa SEO (Meta Tags)</h3>
                    <svg id="seo-chevron" class="w-4 h-4 text-slate-500 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="seo-accordion-content" class="hidden pt-6 space-y-4 border-t border-slate-100 dark:border-zinc-800 mt-4">
                    <div class="space-y-1">
                        <label for="seo_title" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tiêu Đề SEO (Meta Title)</label>
                        <input type="text" name="seo_title" id="seo_title" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="Nếu để trống sẽ sử dụng tiêu đề bài viết">
                    </div>
                    <div class="space-y-1">
                        <label for="seo_description" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mô Tả SEO (Meta Description)</label>
                        <textarea name="seo_description" id="seo_description" rows="3" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="Nếu để trống sẽ sử dụng mô tả ngắn bài viết (Tối đa 160 ký tự)"></textarea>
                    </div>
                    <div class="space-y-1">
                        <label for="seo_keywords" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Từ Khóa SEO (Meta Keywords)</label>
                        <input type="text" name="seo_keywords" id="seo_keywords" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none" placeholder="e.g. convert anh, chuyen anh png, online image converter">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar options & publish triggers (1/3 width) -->
        <div class="space-y-6">
            <!-- Publishing & Status Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Cài Đặt Xuất Bản</h3>
                
                <div class="space-y-1">
                    <label for="status" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Trạng Thái Bài Viết</label>
                    <select name="status" id="status" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="draft">Bản nháp (Draft)</option>
                        <option value="published" selected>Xuất bản ngay (Published)</option>
                    </select>
                </div>
            </div>

            <!-- Upload Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Ảnh Bìa Bài Viết (Featured Image)</h3>
                
                <div class="space-y-2">
                    <div class="border-2 border-dashed border-slate-200 dark:border-zinc-800 hover:border-slate-350 dark:hover:border-zinc-700 rounded-2xl p-6 text-center cursor-pointer relative transition-all" onclick="document.getElementById('image-upload-input').click()">
                        <input type="file" name="image" id="image-upload-input" accept="image/*" class="hidden" onchange="previewFeaturedImage(this)">
                        
                        <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wide block">Chọn hình ảnh</span>
                    </div>

                    <div id="image-preview-container" class="pt-2 hidden">
                        <div class="w-full h-32 rounded-xl border border-slate-200 dark:border-zinc-800 overflow-hidden relative">
                            <img id="featured-image-preview" src="" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit trigger card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3.5 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-indigo-600/10">
                    Xuất Bản Bài Viết
                </button>
                
                <a href="{{ route('admin.posts') }}" class="w-full inline-flex items-center justify-center text-xs font-bold text-slate-500 hover:text-slate-950 dark:text-zinc-400 dark:hover:text-white transition-colors py-2 uppercase tracking-wider">
                    Hủy & Quay Lại
                </a>
            </div>
        </div>
    </form>

    <script>
        function toggleSeoAccordion() {
            const content = document.getElementById('seo-accordion-content');
            const chevron = document.getElementById('seo-chevron');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        function previewFeaturedImage(input) {
            const container = document.getElementById('image-preview-container');
            const preview = document.getElementById('featured-image-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('hidden');
            }
        }
    </script>

@endsection

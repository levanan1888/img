@extends('admin.layouts.app')

@section('title', 'Cấu Hình Hệ Thống')
@section('page-title', 'Cấu Hình')

@section('content')

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- Core website preferences (2/3 width) -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Parameters Card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Cấu Hình Chung</h3>
                
                <div class="space-y-1">
                    <label for="site_name" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tên Website</label>
                    <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>

                <div class="space-y-1">
                    <label for="site_email" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Email Hệ Thống</label>
                    <input type="email" name="site_email" id="site_email" value="{{ $settings['site_email'] }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
            </div>

            <!-- Logo config card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Logo Thương Hiệu</h3>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <!-- Current logo preview -->
                    <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-zinc-950/60 border border-slate-200 dark:border-zinc-800 overflow-hidden flex items-center justify-center shrink-0">
                        @if($settings['site_logo'] && $settings['site_logo'] !== 'logo.png')
                            <img src="{{ asset($settings['site_logo']) }}" class="w-full h-full object-contain p-2">
                        @else
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Logo</span>
                        @endif
                    </div>

                    <div class="flex-grow space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tải lên Logo mới (Chấp nhận: JPG, PNG, SVG)</label>
                        <input type="file" name="site_logo" class="text-xs text-slate-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 dark:file:bg-indigo-950/20 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>
        </div>

        <!-- Localization & Options sidebar (1/3 width) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest border-b border-slate-100 dark:border-zinc-800 pb-3">Giao Diện & Quốc Tế Hóa</h3>
                
                <div class="space-y-1">
                    <label for="theme_mode" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Chế độ giao diện</label>
                    <select name="theme_mode" id="theme_mode" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="light" {{ $settings['theme_mode'] === 'light' ? 'selected' : '' }}>Giao Diện Sáng (Light)</option>
                        <option value="dark" {{ $settings['theme_mode'] === 'dark' ? 'selected' : '' }}>Giao Diện Tối (Dark)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="default_language" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Ngôn ngữ mặc định</label>
                    <select name="default_language" id="default_language" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="vi" {{ $settings['default_language'] === 'vi' ? 'selected' : '' }}>Tiếng Việt (VN)</option>
                        <option value="en" {{ $settings['default_language'] === 'en' ? 'selected' : '' }}>English (US)</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="currency_code" class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Tiền tệ hiển thị</label>
                    <select name="currency_code" id="currency_code" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="VND" {{ $settings['currency_code'] === 'VND' ? 'selected' : '' }}>VND (đ)</option>
                        <option value="USD" {{ $settings['currency_code'] === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ $settings['currency_code'] === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    </select>
                </div>
            </div>

            <!-- Submit trigger card -->
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3.5 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-indigo-600/10">
                    Cập nhật cài đặt
                </button>
            </div>
        </div>
    </form>

@endsection

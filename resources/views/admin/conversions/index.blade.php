@extends('admin.layouts.app')

@section('title', 'Nhật Ký Chuyển Đổi')
@section('page-title', 'Nhật Ký Convert')

@section('content')

    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <!-- Search & Filter Form -->
        <form action="{{ route('admin.conversions') }}" method="GET" class="flex-grow flex flex-wrap items-center gap-3">
            <div class="relative flex-grow max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl pl-9 pr-4 py-2.5 focus:outline-none transition-colors" placeholder="Tìm theo tên tệp tin...">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <select name="status" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Tất Cả Trạng Thái</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Thành Công</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất Bại</option>
            </select>

            <select name="source_format" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Định Dạng Gốc</option>
                @foreach($sourceFormats as $format)
                    <option value="{{ $format }}" {{ request('source_format') === $format ? 'selected' : '' }}>{{ strtoupper($format) }}</option>
                @endforeach
            </select>

            <select name="target_format" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Định Dạng Đích</option>
                @foreach($targetFormats as $format)
                    <option value="{{ $format }}" {{ request('target_format') === $format ? 'selected' : '' }}>{{ strtoupper($format) }}</option>
                @endforeach
            </select>

            @if(request('search') || request('status') || request('source_format') || request('target_format'))
                <a href="{{ route('admin.conversions') }}" class="text-xs font-bold text-rose-500 hover:text-rose-600 uppercase tracking-wider py-2">Xóa Bộ Lọc</a>
            @endif
        </form>

        <div class="flex items-center gap-3">
            <!-- Export CSV -->
            <a href="{{ route('admin.conversions.export') }}" class="inline-flex items-center justify-center gap-2 text-xs font-bold text-slate-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 hover:bg-slate-50 dark:hover:bg-zinc-800 border border-slate-200 dark:border-zinc-800 py-3 px-6 rounded-xl transition-all uppercase tracking-widest shadow-sm">
                Xuất CSV
            </a>

            <!-- Clear Logs -->
            @if(auth()->user()->hasPermission('conversions', 'delete'))
                <form action="{{ route('admin.conversions.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa SẠCH toàn bộ nhật ký chuyển đổi?');" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center text-xs font-bold text-white bg-rose-650 hover:bg-rose-500 py-3 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-rose-600/10">
                        Xóa Sạch
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Data Grid Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-200 dark:border-zinc-800">
                    <tr>
                        <th class="py-4 px-6">Tên Tệp Tin</th>
                        <th class="py-4 px-6">Kích Thước</th>
                        <th class="py-4 px-6">Định Dạng</th>
                        <th class="py-4 px-6">Thời Gian Chạy</th>
                        <th class="py-4 px-6">IP Khách</th>
                        <th class="py-4 px-6">Trạng Thái</th>
                        <th class="py-4 px-6">Lỗi Phát Sinh</th>
                        <th class="py-4 px-6">Thời Gian Tạo</th>
                        <th class="py-4 px-6 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/25 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white truncate max-w-[200px]" title="{{ $log->file_name }}">
                                {{ $log->file_name }}
                            </td>
                            <td class="py-3.5 px-6 font-mono text-slate-500 dark:text-zinc-400">
                                {{ number_format($log->file_size / 1024, 1) }} KB
                            </td>
                            <td class="py-3.5 px-6 font-semibold uppercase">
                                <span class="text-indigo-600 dark:text-indigo-400">{{ $log->source_format }}</span>
                                <span class="text-slate-300 dark:text-zinc-800">&rarr;</span>
                                <span class="text-emerald-600 dark:text-emerald-400">{{ $log->target_format }}</span>
                            </td>
                            <td class="py-3.5 px-6 font-mono font-bold">{{ number_format($log->execution_time, 3) }} giây</td>
                            <td class="py-3.5 px-6 font-mono text-slate-500 dark:text-zinc-500">{{ $log->ip_address }}</td>
                            <td class="py-3.5 px-6">
                                @if($log->status === 'success')
                                    <span class="inline-flex items-center text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-md uppercase tracking-wider">Thành Công</span>
                                @else
                                    <span class="inline-flex items-center text-[9px] font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/60 px-2 py-0.5 rounded-md uppercase tracking-wider">Thất Bại</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 max-w-[150px] truncate text-rose-500 dark:text-rose-400 font-medium" title="{{ $log->error_message }}">
                                {{ $log->error_message ?: '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 dark:text-zinc-500">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                @if(auth()->user()->hasPermission('conversions', 'delete'))
                                    <form action="{{ route('admin.conversions.delete', $log->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn muốn xóa nhật ký này?');">
                                        @csrf
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-450 font-bold transition-colors">Xóa</button>
                                    </form>
                                @else
                                    <span class="text-slate-300 dark:text-zinc-800">Không hỗ trợ</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 dark:text-zinc-500 font-semibold">Chưa có bản ghi nhật ký chuyển đổi nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

@endsection

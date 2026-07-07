@extends('admin.layouts.app')

@section('title', 'Tổng Quan Hệ Thống')
@section('page-title', 'Tổng Quan')

@section('content')

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total conversions -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-0.5">Tổng Lượt Convert</span>
                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ number_format($totalConversions) }}</span>
            </div>
        </div>

        <!-- Card 2: Success Rate -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-0.5">Tỷ Lệ Thành Công</span>
                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ number_format($successRate, 1) }}%</span>
            </div>
        </div>

        <!-- Card 3: Avg execution time -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-0.5">Thời Gian Xử Lý TB</span>
                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ number_format($avgExecutionTime, 3) }}s</span>
            </div>
        </div>

        <!-- Card 4: Customers -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-sky-50 dark:bg-sky-950/20 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-0.5">Tổng Người Dùng</span>
                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ number_format($totalUsers) }}</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Conversion Chart -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Thống Kê Chuyển Đổi Ảnh</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider mt-0.5">Số lượt convert ảnh hàng ngày (30 ngày)</p>
                </div>
                <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-1 rounded-xl uppercase tracking-wider">Conversions</span>
            </div>
            
            <div class="h-56 relative w-full pt-4">
                <svg viewBox="0 0 1000 200" preserveAspectRatio="none" class="w-full h-full">
                    <line x1="0" y1="30" x2="1000" y2="30" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <line x1="0" y1="105" x2="1000" y2="105" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <line x1="0" y1="180" x2="1000" y2="180" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <polygon fill="rgba(16, 185, 129, 0.06)" points="{{ $conversionsAreaStr }}" />
                    <polyline fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="{{ $conversionsPointsStr }}" />
                </svg>
            </div>
            <div class="flex justify-between text-[8px] font-bold text-slate-400 dark:text-zinc-600 uppercase tracking-widest mt-2 px-1">
                <span>{{ $conversionsData[0]['label'] }}</span>
                <span>{{ $conversionsData[15]['label'] }}</span>
                <span>{{ $conversionsData[29]['label'] }}</span>
            </div>
        </div>

        <!-- Users Chart -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Người Dùng Đăng Ký</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider mt-0.5">Số lượng khách hàng mới tham gia (30 ngày)</p>
                </div>
                <span class="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/20 px-2.5 py-1 rounded-xl uppercase tracking-wider">Signups</span>
            </div>
            
            <div class="h-56 relative w-full pt-4">
                <svg viewBox="0 0 1000 200" preserveAspectRatio="none" class="w-full h-full">
                    <line x1="0" y1="30" x2="1000" y2="30" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <line x1="0" y1="105" x2="1000" y2="105" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <line x1="0" y1="180" x2="1000" y2="180" stroke="currentColor" class="text-slate-100 dark:text-zinc-800" stroke-dasharray="3" />
                    <polygon fill="rgba(99, 102, 241, 0.06)" points="{{ $signupsAreaStr }}" />
                    <polyline fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="{{ $signupsPointsStr }}" />
                </svg>
            </div>
            <div class="flex justify-between text-[8px] font-bold text-slate-400 dark:text-zinc-600 uppercase tracking-widest mt-2 px-1">
                <span>{{ $signupsData[0]['label'] }}</span>
                <span>{{ $signupsData[15]['label'] }}</span>
                <span>{{ $signupsData[29]['label'] }}</span>
            </div>
        </div>
    </div>

    <!-- Data Split tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent conversions (2/3 width) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Lượt Chuyển Đổi Gần Đây</h3>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider mt-0.5">Theo dõi lịch sử xử lý ảnh của toàn hệ thống</p>
                </div>
                @if(auth()->user()->hasPermission('conversions', 'read'))
                    <a href="{{ route('admin.conversions') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 uppercase tracking-widest">
                        Xem Tất Cả &rarr;
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                    <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-200 dark:border-zinc-800">
                        <tr>
                            <th class="py-4 px-6">Tên Tệp Tin</th>
                            <th class="py-4 px-6">Dung Lượng</th>
                            <th class="py-4 px-6">Luồng Chuyển</th>
                            <th class="py-4 px-6">Thời Gian Chạy</th>
                            <th class="py-4 px-6">Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                        @forelse($recentLogs as $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/25 transition-colors">
                                <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white truncate max-w-[200px]" title="{{ $log->file_name }}">
                                    {{ $log->file_name }}
                                </td>
                                <td class="py-3.5 px-6 font-mono text-slate-500">
                                    {{ number_format($log->file_size / 1024, 1) }} KB
                                </td>
                                <td class="py-3.5 px-6 font-semibold uppercase">
                                    <span class="text-indigo-600 dark:text-indigo-400">{{ $log->source_format }}</span>
                                    <span class="text-slate-300 dark:text-zinc-850">&rarr;</span>
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ $log->target_format }}</span>
                                </td>
                                <td class="py-3.5 px-6 font-mono font-bold">{{ number_format($log->execution_time, 3) }}s</td>
                                <td class="py-3.5 px-6">
                                    @if($log->status === 'success')
                                        <span class="inline-flex items-center text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-md">Thành Công</span>
                                    @else
                                        <span class="inline-flex items-center text-[9px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-widest bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/60 px-2 py-0.5 rounded-md" title="{{ $log->error_message }}">Thất Bại</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 dark:text-zinc-500 font-semibold">Chưa ghi nhận lượt chuyển đổi nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users (1/3 width) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Đăng Ký Mới</h3>
                        <p class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider mt-0.5">Khách hàng mới tạo tài khoản gần đây</p>
                    </div>
                    @if(auth()->user()->hasPermission('users', 'read'))
                        <a href="{{ route('admin.users') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 uppercase tracking-widest">
                            Tất Cả &rarr;
                        </a>
                    @endif
                </div>

                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-100 dark:border-zinc-850">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-zinc-800 border border-slate-350 dark:border-zinc-700 flex items-center justify-center font-bold text-xs text-slate-600 dark:text-zinc-400">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white block truncate">{{ $user->name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 block truncate font-mono lowercase">{{ $user->email }}</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-bold text-slate-400 dark:text-zinc-650 uppercase tracking-wider">
                                {{ $user->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 dark:text-zinc-500 font-semibold text-xs">Chưa có người dùng nào đăng ký.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection

@extends('admin.layouts.app')

@section('title', 'Phân Quyền Vai Trò')
@section('page-title', 'Vai Trò & Quyền Hạn')

@section('content')

    <div class="space-y-6">
        @foreach($roles as $role)
            <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-4 mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            {{ $role->name }}
                            @if($role->name === 'Admin')
                                <span class="text-[8px] font-bold text-white bg-indigo-600 px-1.5 py-0.5 rounded uppercase tracking-wider">Hệ thống</span>
                            @endif
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-0.5">{{ $role->description }}</p>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-zinc-500 bg-slate-50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 px-3 py-1 rounded-xl">
                        {{ $role->users_count }} tài khoản hoạt động
                    </span>
                </div>

                @if($role->name === 'Admin')
                    <div class="bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100 dark:border-indigo-900/60 text-indigo-700 dark:text-indigo-400 text-xs font-semibold rounded-2xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-5.7L18.75 6.5a2.25 2.25 0 011.5 2.25v6.75a2.25 2.25 0 01-1.5 2.25L12 20.25a2.25 2.25 0 01-3 0L3.75 17.25a2.25 2.25 0 01-1.5-2.25V8.75a2.25 2.25 0 011.5-2.25L12 4.05z" />
                        </svg>
                        <span>Vai trò Quản Trị Viên (Admin) nắm toàn quyền kiểm soát hệ thống và không thể thay đổi phân quyền.</span>
                    </div>
                @else
                    <!-- Matrix Form -->
                    <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="overflow-x-auto border border-slate-100 dark:border-zinc-800/80 rounded-2xl">
                            <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                                <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-100 dark:border-zinc-800">
                                    <tr>
                                        <th class="py-3 px-6 w-1/3">Phân Hệ (Module)</th>
                                        <th class="py-3 px-6 text-center">Thêm mới (Create)</th>
                                        <th class="py-3 px-6 text-center">Xem (Read)</th>
                                        <th class="py-3 px-6 text-center">Cập nhật (Update)</th>
                                        <th class="py-3 px-6 text-center">Xóa (Delete)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60 font-semibold">
                                    @foreach($modules as $module)
                                        <tr class="hover:bg-slate-50/20 dark:hover:bg-zinc-850/10">
                                            <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[10px]">
                                                @if($module === 'users')
                                                    Người dùng (Users)
                                                @elseif($module === 'conversions')
                                                    Nhật ký convert (Conversions)
                                                @else
                                                    Cấu hình hệ thống (Settings)
                                                @endif
                                            </td>
                                            @foreach($actions as $action)
                                                <td class="py-3.5 px-6 text-center">
                                                    <input type="checkbox" name="permissions[{{ $module }}][{{ $action }}]" class="h-4 w-4 rounded border-slate-350 dark:border-zinc-700 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-zinc-800" {{ isset($role->permissions[$module]) && in_array($action, $role->permissions[$module]) ? 'checked' : '' }}>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-2.5 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-indigo-600/10">
                                Cập nhật phân quyền
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

@endsection

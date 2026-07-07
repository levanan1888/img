@extends('admin.layouts.app')

@section('title', 'Quản Lý Tài Khoản')
@section('page-title', 'Người Dùng')

@section('content')

    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <!-- Search & Filter Form -->
        <form action="{{ route('admin.users') }}" method="GET" class="flex-grow flex flex-wrap items-center gap-3">
            <div class="relative flex-grow max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl pl-9 pr-4 py-2.5 focus:outline-none transition-colors" placeholder="Tìm theo tên hoặc địa chỉ email...">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <select name="status" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Tất Cả Trạng Thái</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Chưa kích hoạt</option>
                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Đang bị khóa</option>
            </select>

            <select name="role_id" onchange="this.form.submit()" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 text-slate-700 dark:text-zinc-300 text-xs rounded-xl px-3 py-2.5 focus:outline-none">
                <option value="">Tất Cả Vai Trò</option>
                <option value="customer" {{ request('role_id') === 'customer' ? 'selected' : '' }}>Khách Hàng</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>

            @if(request('search') || request('status') || request('role_id'))
                <a href="{{ route('admin.users') }}" class="text-xs font-bold text-rose-500 hover:text-rose-600 uppercase tracking-wider py-2">Xóa Bộ Lọc</a>
            @endif
        </form>

        <!-- Register Button -->
        @if(auth()->user()->hasPermission('users', 'create'))
            <button onclick="toggleRegisterDrawer()" class="inline-flex items-center justify-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 px-6 rounded-xl transition-all uppercase tracking-widest shadow-md shadow-indigo-600/10">
                Tạo Thành Viên
            </button>
        @endif
    </div>

    <!-- Data Grid Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs text-slate-700 dark:text-zinc-300">
                <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-400 dark:text-zinc-500 uppercase text-[9px] font-bold tracking-widest border-b border-slate-200 dark:border-zinc-800">
                    <tr>
                        <th class="py-4 px-6">Họ Và Tên</th>
                        <th class="py-4 px-6">Địa Chỉ Email</th>
                        <th class="py-4 px-6">Vai Trò</th>
                        <th class="py-4 px-6">Trạng Thái</th>
                        <th class="py-4 px-6">Ngày Tham Gia</th>
                        <th class="py-4 px-6 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-zinc-800/25 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white cursor-pointer" onclick="toggleDetailsDrawer('{{ $user->id }}')">
                                {{ $user->name }}
                            </td>
                            <td class="py-3.5 px-6 font-mono text-slate-500 dark:text-zinc-400">{{ $user->email }}</td>
                            <td class="py-3.5 px-6 font-semibold">
                                @if($user->role)
                                    <span class="text-indigo-600 dark:text-indigo-400">{{ $user->role->name }}</span>
                                @else
                                    <span class="text-slate-400 dark:text-zinc-500">Khách Hàng</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/60 px-2 py-0.5 rounded-md uppercase tracking-wider">Đang hoạt động</span>
                                @elseif($user->status === 'blocked')
                                    <span class="inline-flex items-center text-[9px] font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/60 px-2 py-0.5 rounded-md uppercase tracking-wider">Đã Khóa</span>
                                @else
                                    <span class="inline-flex items-center text-[9px] font-bold text-slate-500 dark:text-zinc-500 bg-slate-100 dark:bg-zinc-950/20 border border-slate-200 dark:border-zinc-800 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $user->status }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-slate-500 dark:text-zinc-500">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-3.5 px-6 text-right space-x-1.5 whitespace-nowrap">
                                <button onclick="toggleDetailsDrawer('{{ $user->id }}')" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold transition-colors">Chi tiết</button>
                                
                                @if(auth()->user()->hasPermission('users', 'update'))
                                    <span>&middot;</span>
                                    <button onclick="toggleEditDrawer('{{ $user->id }}')" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-bold transition-colors">Sửa</button>
                                    <span>&middot;</span>
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 font-bold transition-colors">
                                            {{ $user->status === 'active' ? 'Khóa' : 'Mở Khóa' }}
                                        </button>
                                    </form>
                                    <span>&middot;</span>
                                    <button onclick="toggleResetModal('{{ $user->id }}', '{{ $user->name }}')" class="text-slate-500 hover:text-slate-950 dark:hover:text-white font-bold transition-colors">Reset PW</button>
                                @endif

                                @if(auth()->user()->hasPermission('users', 'delete'))
                                    <span>&middot;</span>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn muốn xóa tài khoản thành viên này vĩnh viễn?');">
                                        @csrf
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-450 font-bold transition-colors">Xóa</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-zinc-500 font-semibold">Không tìm thấy tài khoản người dùng hợp lệ.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-950/20">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Drawers & Modals Containers -->

    <!-- Details Slide-over Drawer -->
    @foreach($users as $user)
        <div id="details-drawer-{{ $user->id }}" class="fixed inset-y-0 right-0 z-50 w-full max-w-lg bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out hidden flex-col">
            <div class="h-16 border-b border-slate-200 dark:border-zinc-800 px-6 flex items-center justify-between bg-slate-50 dark:bg-zinc-950/40">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Chi Tiết Tài Khoản</h4>
                <button onclick="toggleDetailsDrawer('{{ $user->id }}')" class="text-slate-500 hover:text-slate-950 dark:hover:text-white">Đóng</button>
            </div>
            <div class="flex-grow overflow-y-auto p-6 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg border border-indigo-200 dark:border-indigo-900">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $user->name }}</h4>
                        <span class="text-xs text-slate-400 dark:text-zinc-500 font-mono">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 border-y border-slate-100 dark:border-zinc-800 py-4 text-xs">
                    <div>
                        <span class="text-slate-400 dark:text-zinc-500 font-bold block mb-1">Vai Trò Hệ Thống</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $user->role ? $user->role->name : 'Khách Hàng' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 dark:text-zinc-500 font-bold block mb-1">Trạng Thái Hoạt Động</span>
                        <span class="font-bold uppercase text-slate-900 dark:text-white">{{ $user->status }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="backdrop-details-{{ $user->id }}" class="fixed inset-0 bg-slate-950/30 backdrop-blur-sm z-40 hidden" onclick="toggleDetailsDrawer('{{ $user->id }}')"></div>
    @endforeach

    <!-- Edit User Slide-over Drawer -->
    @foreach($users as $user)
        <div id="edit-drawer-{{ $user->id }}" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out hidden flex-col">
            <div class="h-16 border-b border-slate-200 dark:border-zinc-800 px-6 flex items-center justify-between bg-slate-50 dark:bg-zinc-950/40">
                <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Sửa Tài Khoản</h4>
                <button onclick="toggleEditDrawer('{{ $user->id }}')" class="text-slate-500 hover:text-slate-950 dark:hover:text-white">Đóng</button>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="flex-grow flex flex-col justify-between">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Họ Và Tên</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Địa chỉ Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Vai Trò</label>
                        <select name="role_id" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                            <option value="">Khách Hàng (Mặc định)</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Trạng Thái</label>
                        <select name="status" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Chưa kích hoạt</option>
                            <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Khóa</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/40">
                    <button type="submit" class="w-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl uppercase tracking-widest shadow-md shadow-indigo-600/10">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
        <div id="backdrop-edit-{{ $user->id }}" class="fixed inset-0 bg-slate-950/30 backdrop-blur-sm z-40 hidden" onclick="toggleEditDrawer('{{ $user->id }}')"></div>
    @endforeach

    <!-- Register Slide-over Drawer -->
    <div id="register-drawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white dark:bg-zinc-900 border-l border-slate-200 dark:border-zinc-800 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out hidden flex-col">
        <div class="h-16 border-b border-slate-200 dark:border-zinc-800 px-6 flex items-center justify-between bg-slate-50 dark:bg-zinc-950/40">
            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest">Tạo Tài Khoản Mới</h4>
            <button onclick="toggleRegisterDrawer()" class="text-slate-500 hover:text-slate-950 dark:hover:text-white">Đóng</button>
        </div>
        <form action="{{ route('admin.users.create') }}" method="POST" class="flex-grow flex flex-col justify-between">
            @csrf
            <div class="p-6 space-y-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Họ Và Tên</label>
                    <input type="text" name="name" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Địa chỉ Email</label>
                    <input type="email" name="email" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mật khẩu ban đầu</label>
                    <input type="password" name="password" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Vai Trò</label>
                    <select name="role_id" class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="">Khách Hàng (Mặc định)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Trạng Thái</label>
                    <select name="status" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Chưa kích hoạt</option>
                        <option value="blocked">Khóa</option>
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950/40">
                <button type="submit" class="w-full text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 py-3 rounded-xl uppercase tracking-widest shadow-md shadow-indigo-600/10">Tạo Tài Khoản</button>
            </div>
        </form>
    </div>
    <div id="backdrop-register" class="fixed inset-0 bg-slate-950/30 backdrop-blur-sm z-40 hidden" onclick="toggleRegisterDrawer()"></div>

    <!-- Reset Password Modal -->
    <div id="reset-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden px-4">
        <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="toggleResetModal()"></div>
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl relative z-10 space-y-4">
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Đặt Lại Mật Khẩu</h4>
                <p class="text-[11px] text-slate-400 dark:text-zinc-500">Tài khoản: <span id="reset-user-name" class="font-bold text-slate-800 dark:text-zinc-300"></span></p>
            </div>
            <form id="reset-password-form" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Mật khẩu mới</label>
                    <input type="password" name="password" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-400 dark:text-zinc-500 uppercase block tracking-wider">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-slate-50/50 dark:bg-zinc-950/40 border border-slate-200 dark:border-zinc-800 focus:border-indigo-500 dark:focus:border-indigo-500 text-slate-900 dark:text-zinc-100 text-xs rounded-xl px-4 py-2.5 focus:outline-none">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="toggleResetModal()" class="text-xs font-bold text-slate-500 dark:text-zinc-400 hover:text-slate-900 uppercase tracking-widest py-2">Hủy</button>
                    <button type="submit" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 px-5 py-2.5 rounded-xl uppercase tracking-widest">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDetailsDrawer(id) {
            const drawer = document.getElementById(`details-drawer-${id}`);
            const backdrop = document.getElementById(`backdrop-details-${id}`);
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

        function toggleEditDrawer(id) {
            const drawer = document.getElementById(`edit-drawer-${id}`);
            const backdrop = document.getElementById(`backdrop-edit-${id}`);
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

        function toggleRegisterDrawer() {
            const drawer = document.getElementById('register-drawer');
            const backdrop = document.getElementById('backdrop-register');
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

        function toggleResetModal(id = '', name = '') {
            const modal = document.getElementById('reset-modal');
            const userNameEl = document.getElementById('reset-user-name');
            const form = document.getElementById('reset-password-form');
            if (modal.classList.contains('hidden')) {
                userNameEl.innerText = name;
                form.action = `/admin/users/${id}/reset-password`;
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>

@endsection

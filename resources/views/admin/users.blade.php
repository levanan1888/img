@extends('admin.layout')

@section('title', 'User Accounts')
@section('page-title', 'User Management')

@section('content')

    <!-- User Management Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Users List (2/3 width) -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-sm overflow-hidden lg:col-span-2 flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-zinc-800">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Registered System Users</h3>
                    <p class="text-[11px] text-zinc-400">Manage user accounts authorized to access the system platform.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-xs text-zinc-300">
                        <thead class="bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider border-b border-zinc-800">
                            <tr>
                                <th class="py-4 px-6">Name</th>
                                <th class="py-4 px-6">Email Address</th>
                                <th class="py-4 px-6">Created At</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/60">
                            @forelse($users as $user)
                                <tr class="hover:bg-zinc-800/25 transition-colors">
                                    <td class="py-3.5 px-6 font-medium text-white">{{ $user->name }}</td>
                                    <td class="py-3.5 px-6 font-mono text-zinc-400">{{ $user->email }}</td>
                                    <td class="py-3.5 px-6 text-zinc-500">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="py-3.5 px-6 text-right">
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user account permanently?');">
                                            @csrf
                                            <button type="submit" class="text-zinc-500 hover:text-red-400 transition-colors focus:outline-none">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-zinc-500 font-medium">No users registered.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create User Form (1/3 width) -->
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-sm flex flex-col justify-between h-fit">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Register New User</h3>
                
                @if($errors->any() && !$errors->has('login_error'))
                    <div class="bg-red-950/50 border border-red-900 text-red-400 text-[11px] rounded-lg p-3.5 mb-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label for="name" class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Full Name</label>
                        <input type="text" name="name" id="name" required class="w-full bg-zinc-950 border border-zinc-800 focus:border-zinc-700 text-zinc-100 text-xs rounded-lg px-3.5 py-2 focus:outline-none transition-colors" placeholder="Full name">
                    </div>
                    <div class="space-y-1">
                        <label for="email" class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Email Address</label>
                        <input type="email" name="email" id="email" required class="w-full bg-zinc-950 border border-zinc-800 focus:border-zinc-700 text-zinc-100 text-xs rounded-lg px-3.5 py-2 focus:outline-none transition-colors" placeholder="user@example.com">
                    </div>
                    <div class="space-y-1">
                        <label for="reg_password" class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider block">Password</label>
                        <input type="password" name="password" id="reg_password" required class="w-full bg-zinc-950 border border-zinc-800 focus:border-zinc-700 text-zinc-100 text-xs rounded-lg px-3.5 py-2 focus:outline-none transition-colors" placeholder="Min 6 characters">
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center text-xs font-bold text-black bg-white hover:bg-zinc-200 py-2.5 px-4 rounded-md transition-colors uppercase tracking-wider shadow-sm">
                        Create Account
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection

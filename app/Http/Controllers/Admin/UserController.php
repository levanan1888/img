<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $roleId = $request->input('role_id');

        $query = User::with('role')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($roleId !== null && $roleId !== '') {
            if ($roleId === 'customer') {
                $query->whereNull('role_id');
            } else {
                $query->where('role_id', $roleId);
            }
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id ?: null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users')->with('success', 'Đã tạo tài khoản thành công!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id ?: null,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users')->with('success', 'Đã cập nhật thông tin tài khoản thành công!');
    }

    public function toggleStatus($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Bạn không thể tự khóa tài khoản của chính mình!']);
        }

        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        $action = $user->status === 'active' ? 'mở khóa' : 'khóa';
        return redirect()->back()->with('success', "Đã {$action} tài khoản thành công.");
    }

    public function resetPassword(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', "Đã đặt lại mật khẩu cho tài khoản {$user->name} thành công.");
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->withErrors(['error' => 'Bạn không thể tự xóa tài khoản của chính mình!']);
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Đã xóa tài khoản thành công.');
    }
}

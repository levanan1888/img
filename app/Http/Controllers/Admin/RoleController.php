<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->get();
        $modules = ['users', 'conversions', 'settings'];
        $actions = ['create', 'read', 'update', 'delete'];
        
        return view('admin.roles.index', compact('roles', 'modules', 'actions'));
    }

    public function updatePermissions(Request $request, $id): RedirectResponse
    {
        $role = Role::findOrFail($id);
        
        if ($role->name === 'Admin') {
            return redirect()->back()->withErrors(['error' => 'Quyền của Quản trị viên (Admin) là mặc định và không thể sửa đổi.']);
        }

        $permissions = $request->input('permissions', []);
        
        $cleanedPermissions = [];
        foreach ($permissions as $module => $actions) {
            $cleanedPermissions[$module] = array_keys($actions);
        }

        $role->update([
            'permissions' => $cleanedPermissions
        ]);

        return redirect()->route('admin.roles')->with('success', 'Đã cập nhật phân quyền vai trò thành công!');
    }
}

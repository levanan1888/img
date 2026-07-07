<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'site_name' => SystemSetting::getValue('site_name', 'Trình chuyển đổi ảnh'),
            'site_email' => SystemSetting::getValue('site_email', 'contact@chuyenanh.com'),
            'site_logo' => SystemSetting::getValue('site_logo', 'logo.png'),
            'theme_mode' => SystemSetting::getValue('theme_mode', 'dark'),
            'default_language' => SystemSetting::getValue('default_language', 'vi'),
            'currency_code' => SystemSetting::getValue('currency_code', 'VND'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,jpg,png,svg|max:2048',
            'theme_mode' => 'required|in:light,dark',
            'default_language' => 'required|in:en,vi',
            'currency_code' => 'required|in:USD,VND,EUR',
        ]);

        SystemSetting::setValue('site_name', $request->site_name);
        SystemSetting::setValue('site_email', $request->site_email);
        SystemSetting::setValue('theme_mode', $request->theme_mode);
        SystemSetting::setValue('default_language', $request->default_language);
        SystemSetting::setValue('currency_code', $request->currency_code);

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $fileName);
            SystemSetting::setValue('site_logo', 'uploads/' . $fileName);
        }

        return redirect()->back()->with('success', 'Đã cập nhật cấu hình hệ thống thành công!');
    }
}

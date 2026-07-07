<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ConversionLogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\ImageConverterController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Client Authentication
Route::get('/login', [ClientAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [ClientAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [ClientAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');

// Public Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/category/{categorySlug}', [BlogController::class, 'index'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Guest Landing Page & Service endpoints
Route::get('/', function () {
    $recentPosts = \App\Models\Post::where('status', 'published')
        ->with('category')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    return view('welcome', compact('recentPosts'));
});

Route::post('/convert/image', ImageConverterController::class)
    ->middleware('throttle:conversions')
    ->name('image.convert');

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// -------------------------------------------------------------
// Admin Authentication (Guest)
// -------------------------------------------------------------
Route::get('/admin/login', [AdminLoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->middleware('throttle:admin-login')
    ->name('admin.login.submit');

// -------------------------------------------------------------
// Admin Controlled Platform (Protected & RBAC Validated)
// -------------------------------------------------------------
Route::middleware(['auth', 'admin.access'])->prefix('admin')->name('admin.')->group(function () {
    
    // Auth Logout
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Dashboard Overview
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Conversion Logs Management
    Route::middleware('admin.access:conversions,read')->group(function () {
        Route::get('/conversions', [ConversionLogController::class, 'index'])->name('conversions');
        Route::get('/conversions/export', [ConversionLogController::class, 'exportCsv'])->name('conversions.export');
    });
    Route::middleware('admin.access:conversions,delete')->group(function () {
        Route::post('/conversions/clear', [ConversionLogController::class, 'clearAll'])->name('conversions.clear');
        Route::post('/conversions/{id}/delete', [ConversionLogController::class, 'destroy'])->name('conversions.delete');
    });

    // User Accounts Management
    Route::middleware('admin.access:users,read')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
    });
    
    Route::middleware('admin.access:users,create')->group(function () {
        Route::post('/users/create', [UserController::class, 'create'])->name('users.create');
    });

    Route::middleware('admin.access:users,update')->group(function () {
        Route::post('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });

    Route::middleware('admin.access:users,delete')->group(function () {
        Route::post('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.delete');
    });

    // Blog Management
    Route::middleware('admin.access:conversions,read')->group(function () {
        Route::get('/posts', [PostController::class, 'index'])->name('posts');
        Route::get('/posts/create', [PostController::class, 'createView'])->name('posts.create');
        Route::get('/posts/{id}/edit', [PostController::class, 'editView'])->name('posts.edit');
        Route::get('/blog-categories', [BlogCategoryController::class, 'index'])->name('blog-categories');
    });
    Route::middleware('admin.access:conversions,create')->group(function () {
        Route::post('/posts/create', [PostController::class, 'create'])->name('posts.store');
        Route::post('/blog-categories/create', [BlogCategoryController::class, 'create'])->name('blog-categories.create');
    });
    Route::middleware('admin.access:conversions,update')->group(function () {
        Route::post('/posts/{id}/update', [PostController::class, 'update'])->name('posts.update');
        Route::post('/blog-categories/{id}/update', [BlogCategoryController::class, 'update'])->name('blog-categories.update');
    });
    Route::middleware('admin.access:conversions,delete')->group(function () {
        Route::post('/posts/{id}/delete', [PostController::class, 'destroy'])->name('posts.delete');
        Route::post('/blog-categories/{id}/delete', [BlogCategoryController::class, 'destroy'])->name('blog-categories.delete');
    });

    // Roles and RBAC Management
    Route::middleware('admin.access:settings,read')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    });

    Route::middleware('admin.access:settings,update')->group(function () {
        Route::post('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    });
});

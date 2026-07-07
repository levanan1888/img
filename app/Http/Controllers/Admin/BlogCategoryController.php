<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = BlogCategory::withCount('posts')->orderBy('name', 'asc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        BlogCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        Cache::forget('blog_categories_for_public_pages');
        Cache::put('public_blog_cache_version', ((int) Cache::get('public_blog_cache_version', 1)) + 1);

        return redirect()->route('admin.blog-categories')->with('success', 'Đã thêm danh mục bài viết thành công!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
        ]);

        Cache::forget('blog_categories_for_public_pages');
        Cache::put('public_blog_cache_version', ((int) Cache::get('public_blog_cache_version', 1)) + 1);

        return redirect()->route('admin.blog-categories')->with('success', 'Đã cập nhật danh mục bài viết thành công!');
    }

    public function destroy($id): RedirectResponse
    {
        $category = BlogCategory::findOrFail($id);
        
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.blog-categories')->withErrors(['error' => 'Không thể xóa danh mục này vì đang có bài viết thuộc về danh mục này.']);
        }

        $category->delete();
        Cache::forget('blog_categories_for_public_pages');
        Cache::put('public_blog_cache_version', ((int) Cache::get('public_blog_cache_version', 1)) + 1);

        return redirect()->route('admin.blog-categories')->with('success', 'Đã xóa danh mục bài viết thành công.');
    }
}

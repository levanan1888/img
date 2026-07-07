<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Post::with('author', 'category')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function createView(): View
    {
        $categories = BlogCategory::orderBy('name', 'asc')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:blog_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('uploads/blog'))) {
                mkdir(public_path('uploads/blog'), 0777, true);
            }
            
            $file->move(public_path('uploads/blog'), $fileName);
            $imagePath = 'uploads/blog/' . $fileName;
        }

        Post::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'summary' => $request->summary ?: Str::limit(strip_tags($request->content), 150),
            'image_path' => $imagePath,
            'status' => $request->status,
            'author_id' => auth()->id(),
            'category_id' => $request->category_id,
            'seo_title' => $request->seo_title ?: $request->title,
            'seo_description' => $request->seo_description ?: Str::limit(strip_tags($request->content), 160),
            'seo_keywords' => $request->seo_keywords,
        ]);

        Cache::put('public_blog_cache_version', ((int) Cache::get('public_blog_cache_version', 1)) + 1);

        return redirect()->route('admin.posts')->with('success', 'Đã xuất bản blog thành công!');
    }

    public function editView($id): View
    {
        $post = Post::findOrFail($id);
        $categories = BlogCategory::orderBy('name', 'asc')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:blog_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $imagePath = $post->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            if (!file_exists(public_path('uploads/blog'))) {
                mkdir(public_path('uploads/blog'), 0777, true);
            }
            
            $file->move(public_path('uploads/blog'), $fileName);
            $imagePath = 'uploads/blog/' . $fileName;
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'summary' => $request->summary ?: Str::limit(strip_tags($request->content), 150),
            'image_path' => $imagePath,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'seo_title' => $request->seo_title ?: $request->title,
            'seo_description' => $request->seo_description ?: Str::limit(strip_tags($request->content), 160),
            'seo_keywords' => $request->seo_keywords,
        ]);

        Cache::put('public_blog_cache_version', ((int) Cache::get('public_blog_cache_version', 1)) + 1);

        return redirect()->route('admin.posts')->with('success', 'Đã cập nhật blog thành công!');
    }

    public function destroy($id): RedirectResponse
    {
        $post = Post::findOrFail($id);
        
        if ($post->image_path && file_exists(public_path($post->image_path))) {
            @unlink(public_path($post->image_path));
        }

        $post->delete();
        return redirect()->route('admin.posts')->with('success', 'Đã xóa bài viết thành công.');
    }
}

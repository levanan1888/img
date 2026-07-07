<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null): View
    {
        $categories = $this->getCachedCategories();
        $currentCategory = null;

        $query = Post::query()
            ->select([
                'id',
                'title',
                'slug',
                'summary',
                'image_path',
                'category_id',
                'created_at',
            ])
            ->where('status', 'published')
            ->with(['category:id,name,slug'])
            ->latest();

        if ($categorySlug !== null) {
            $currentCategory = BlogCategory::query()
                ->select(['id', 'name', 'slug', 'description'])
                ->where('slug', $categorySlug)
                ->firstOrFail();

            $query->where('category_id', $currentCategory->id);
        }

        $page = max((int) $request->query('page', 1), 1);
        $cacheKey = 'public_blog_posts:' . ($categorySlug ?: 'all') . ':page:' . $page;

        $posts = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($query, $page) {
            return $query->simplePaginate(9, ['*'], 'page', $page);
        })->withQueryString();

        return view('blog.index', compact('posts', 'categories', 'currentCategory'));
    }

    public function show(string $slug): View
    {
        $post = Cache::remember('public_blog_post:' . $slug, now()->addMinutes(5), function () use ($slug) {
            return Post::query()
                ->where('slug', $slug)
                ->where('status', 'published')
                ->with(['category:id,name,slug'])
                ->firstOrFail();
        });

        $categories = $this->getCachedCategories();

        return view('blog.show', compact('post', 'categories'));
    }

    private function getCachedCategories()
    {
        return Cache::remember('blog_categories_for_public_pages', now()->addMinutes(10), function () {
            return BlogCategory::query()
                ->select(['id', 'name', 'slug', 'description'])
                ->orderBy('name')
                ->get();
        });
    }
}
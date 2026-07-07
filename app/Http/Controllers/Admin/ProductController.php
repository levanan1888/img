<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');

        $query = Product::with('category', 'primaryImage')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function createView(): View
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name), '-'));
        
        // Ensure slug uniqueness
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->stock > 0 ? $request->status : 'out_of_stock',
            'category_id' => $request->category_id,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
        ]);

        // Ensure directories exist
        if (!file_exists(public_path('uploads/products'))) {
            mkdir(public_path('uploads/products'), 0777, true);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $fileName);
                $path = 'uploads/products/' . $fileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        } else {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'placeholder.png',
                'is_primary' => true
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product published successfully!');
    }

    public function editView($id): View
    {
        $product = Product::with('images', 'category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name), '-'));
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->stock > 0 ? $request->status : 'out_of_stock',
            'category_id' => $request->category_id,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
        ]);

        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            
            // Ensure directories exist
            if (!file_exists(public_path('uploads/products'))) {
                mkdir(public_path('uploads/products'), 0777, true);
            }

            foreach ($request->file('images') as $index => $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $fileName);
                $path = 'uploads/products/' . $fileName;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0
                ]);
                $hasPrimary = true;
            }
        }

        return redirect()->route('admin.products')->with('success', 'Product details updated successfully!');
    }

    public function deleteImage($id): RedirectResponse
    {
        $img = ProductImage::findOrFail($id);
        $productId = $img->product_id;

        if ($img->image_path !== 'placeholder.png' && file_exists(public_path($img->image_path))) {
            @unlink(public_path($img->image_path));
        }

        $wasPrimary = $img->is_primary;
        $img->delete();

        if ($wasPrimary) {
            $nextImg = ProductImage::where('product_id', $productId)->first();
            if ($nextImg) {
                $nextImg->is_primary = true;
                $nextImg->save();
            }
        }

        return redirect()->back()->with('success', 'Product image removed successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        foreach ($product->images as $img) {
            if ($img->image_path !== 'placeholder.png' && file_exists(public_path($img->image_path))) {
                @unlink(public_path($img->image_path));
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product removed successfully.');
    }
}

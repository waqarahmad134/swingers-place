<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Blog::with(['author', 'categories', 'tags'])->latest();
        
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }
        
        $posts = $query->paginate(20);
        $search = $request->search ?? '';
        
        return view('admin.blog.index', compact('posts', 'search'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs,slug'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'allow_indexing' => ['nullable', 'boolean'],
            'template' => ['nullable', 'integer', 'min:1', 'max:3'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Blog::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['author_id'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_indexing'] = $request->has('allow_indexing');
        $validated['template'] = $request->input('template', 1);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            // Ensure directory exists
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['featured_image'] = $imagePath;
        } else {
            unset($validated['featured_image']);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $image = $request->file('og_image');
            $imageName = 'og_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['og_image'] = $imagePath;
        } else {
            unset($validated['og_image']);
        }

        // Handle Twitter image upload
        if ($request->hasFile('twitter_image')) {
            $image = $request->file('twitter_image');
            $imageName = 'twitter_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['twitter_image'] = $imagePath;
        } else {
            unset($validated['twitter_image']);
        }

        $blog = Blog::create($validated);

        // Attach categories and tags
        if ($request->has('categories')) {
            $blog->categories()->sync($request->categories);
        }

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully!');
    }

    public function show(Blog $blog): View
    {
        $blog->load(['author', 'categories', 'tags']);
        return view('admin.blog.show', compact('blog'));
    }

    public function edit(Blog $blog): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $blog->load(['categories', 'tags']);
        
        return view('admin.blog.edit', compact('blog', 'categories', 'tags'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blogs,slug,' . $blog->id],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'allow_indexing' => ['nullable', 'boolean'],
            'template' => ['nullable', 'integer', 'min:1', 'max:3'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness (excluding current blog)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Blog::where('slug', $validated['slug'])->where('id', '!=', $blog->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_indexing'] = $request->has('allow_indexing');
        $validated['template'] = $request->input('template', $blog->template ?? 1);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
                File::delete(public_path($blog->featured_image));
            }
            
            $image = $request->file('featured_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            // Ensure directory exists
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['featured_image'] = $imagePath;
        } else {
            unset($validated['featured_image']);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            // Delete old image if exists
            if ($blog->og_image && File::exists(public_path($blog->og_image))) {
                File::delete(public_path($blog->og_image));
            }
            
            $image = $request->file('og_image');
            $imageName = 'og_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['og_image'] = $imagePath;
        } else {
            unset($validated['og_image']);
        }

        // Handle Twitter image upload
        if ($request->hasFile('twitter_image')) {
            // Delete old image if exists
            if ($blog->twitter_image && File::exists(public_path($blog->twitter_image))) {
                File::delete(public_path($blog->twitter_image));
            }
            
            $image = $request->file('twitter_image');
            $imageName = 'twitter_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/blogs/' . $imageName;
            
            $uploadPath = public_path('images/blogs');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['twitter_image'] = $imagePath;
        } else {
            unset($validated['twitter_image']);
        }

        $blog->update($validated);

        // Sync categories and tags
        $blog->categories()->sync($request->categories ?? []);
        $blog->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully!');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        // Delete associated images
        if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {
            File::delete(public_path($blog->featured_image));
        }
        if ($blog->og_image && File::exists(public_path($blog->og_image))) {
            File::delete(public_path($blog->og_image));
        }
        if ($blog->twitter_image && File::exists(public_path($blog->twitter_image))) {
            File::delete(public_path($blog->twitter_image));
        }
        
        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}

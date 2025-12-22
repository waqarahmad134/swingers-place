<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::with(['author', 'categories', 'tags'])
            ->where('is_published', true)
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('blogs')->orderBy('name')->get();
        $tags = Tag::withCount('blogs')->orderBy('name')->get();

        return view('blogs.index', compact('blogs', 'categories', 'tags'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::with(['author', 'categories', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment views
        $blog->increment('views');

        $template = $blog->template ?? 1;
        $templateView = 'blogs.templates.template-' . $template;

        // Fallback to template-1 if template view doesn't exist
        if (!view()->exists($templateView)) {
            $templateView = 'blogs.templates.template-1';
        }

        // Get related posts
        $relatedBlogs = Blog::with(['author', 'categories'])
            ->where('is_published', true)
            ->where('id', '!=', $blog->id)
            ->whereHas('categories', function ($query) use ($blog) {
                $query->whereIn('categories.id', $blog->categories->pluck('id'));
            })
            ->latest()
            ->limit(3)
            ->get();

        return view('blogs.show', compact('blog', 'templateView', 'relatedBlogs'));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $blogs = Blog::with(['author', 'categories', 'tags'])
            ->where('is_published', true)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('blogs')->orderBy('name')->get();
        $tags = Tag::withCount('blogs')->orderBy('name')->get();

        return view('blogs.category', compact('category', 'blogs', 'categories', 'tags'));
    }

    public function tag(string $slug): View
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        
        $blogs = Blog::with(['author', 'categories', 'tags'])
            ->where('is_published', true)
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('blogs')->orderBy('name')->get();
        $tags = Tag::withCount('blogs')->orderBy('name')->get();

        return view('blogs.tag', compact('tag', 'blogs', 'categories', 'tags'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Get the correct route prefix based on user role
     */
    protected function getRoutePrefix(): string
    {
        $user = Auth::user();
        return ($user && $user->is_editor) ? 'editor' : 'admin';
    }

    public function index(): View
    {
        $pages = Page::orderBy('title')->get();
        
        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug'],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'string', 'max:500'],
            'allow_indexing' => ['nullable', 'boolean'],
            'template' => ['nullable', 'integer', 'min:1', 'max:3'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Page::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['allow_indexing'] = $request->has('allow_indexing');
        $validated['template'] = $request->input('template', 1);

        Page::create($validated);

        return redirect()->route($this->getRoutePrefix() . '.pages.index')
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pages,slug,' . $page->id],
            'content' => ['required', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'string', 'max:500'],
            'allow_indexing' => ['nullable', 'boolean'],
            'template' => ['nullable', 'integer', 'min:1', 'max:3'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness (excluding current page)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Page::where('slug', $validated['slug'])->where('id', '!=', $page->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['allow_indexing'] = $request->has('allow_indexing');
        $validated['template'] = $request->input('template', $page->template ?? 1);

        $page->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route($this->getRoutePrefix() . '.pages.index')
            ->with('success', 'Page deleted successfully!');
    }
}


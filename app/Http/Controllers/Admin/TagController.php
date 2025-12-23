<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController extends Controller
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
        $tags = Tag::withCount('blogs')->orderBy('name')->get();
        
        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
            'description' => ['nullable', 'string'],
        ]);

        // Generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure uniqueness
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Tag::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        Tag::create($validated);

        return redirect()->route($this->getRoutePrefix() . '.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,' . $tag->id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug,' . $tag->id],
            'description' => ['nullable', 'string'],
        ]);

        // Generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure uniqueness (excluding current tag)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Tag::where('slug', $validated['slug'])->where('id', '!=', $tag->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $tag->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route($this->getRoutePrefix() . '.tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}

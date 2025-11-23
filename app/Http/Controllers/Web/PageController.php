<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function about(): View
    {
        $page = Page::where('slug', 'about')
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function contact(): View
    {
        $page = Page::where('slug', 'contact')
            ->where('is_active', true)
            ->first();

        if ($page) {
            return view('pages.contact', compact('page'));
        }

        // Fallback to static view if page doesn't exist in database
        return view('pages.contact');
    }

    public function contactSubmit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Here you can add email sending logic or save to database
        // For now, we'll just redirect with success message
        
        return redirect()->to('/contact')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    public function privacy(): View
    {
        $page = Page::where('slug', 'privacy')
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function terms(): View
    {
        $page = Page::where('slug', 'terms')
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}


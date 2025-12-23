<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContentSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentManagementController extends Controller
{
    public function index(): View
    {
        // Group sections by category for better organization
        $sections = HomeContentSection::orderBy('order')->get();
        
        $groupedSections = [
            'Hero Section' => $sections->whereIn('section_key', [
                'hero_title', 'hero_title_highlight', 'hero_title_ending', 
                'hero_subtitle', 'hero_cta_text'
            ])->values(),
            'Search Section' => $sections->whereIn('section_key', [
                'search_title', 'search_description'
            ])->values(),
            'Member Section' => $sections->whereIn('section_key', [
                'member_title', 'member_description',
                'member_card_1_title', 'member_card_1_description', 'member_card_1_button',
                'member_card_2_title', 'member_card_2_description', 'member_card_2_button',
                'member_card_3_title', 'member_card_3_description', 'member_card_3_button'
            ])->values(),
            'Journey Section' => $sections->whereIn('section_key', [
                'journey_title',
                'journey_step_1_title', 'journey_step_1_description',
                'journey_step_2_title', 'journey_step_2_description',
                'journey_step_3_title', 'journey_step_3_description',
                'journey_step_4_title', 'journey_step_4_description'
            ])->values(),
            'Date Section' => $sections->whereIn('section_key', [
                'date_title', 'date_description', 'date_button',
                'date_stat_1_number', 'date_stat_1_label',
                'date_stat_2_number', 'date_stat_2_label',
                'date_stat_3_number', 'date_stat_3_label',
                'date_stat_4_number', 'date_stat_4_label'
            ])->values(),
            'Grid Section' => $sections->whereIn('section_key', [
                'grid_card_1_title', 'grid_card_1_description',
                'grid_card_2_title', 'grid_card_2_description',
                'grid_card_3_title', 'grid_card_3_description',
                'grid_card_4_title', 'grid_card_4_description',
                'grid_card_5_title', 'grid_card_5_description',
                'grid_card_6_title', 'grid_card_6_description',
                'grid_card_7_title', 'grid_card_7_description',
                'grid_card_8_title', 'grid_card_8_description',
                'grid_card_9_title', 'grid_card_9_description',
                'grid_card_10_title', 'grid_card_10_description',
                'grid_card_11_title', 'grid_card_11_description',
                'grid_footer_title', 'grid_footer_description'
            ])->values(),
        ];
        
        return view('admin.content-management.index', compact('groupedSections'));
    }
    
    public function edit($id): View
    {
        $section = HomeContentSection::findOrFail($id);
        return view('admin.content-management.edit', compact('section'));
    }
    
    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $section = HomeContentSection::findOrFail($id);
        
        $validated = $request->validate([
            'content' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        
        $section->update([
            'content' => $validated['content'],
            'is_active' => $request->has('is_active'),
        ]);
        
        // If it's an AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Content updated successfully!',
                'section' => $section
            ]);
        }
        
        return redirect()->route('admin.content-management.index')
            ->with('success', 'Content updated successfully!');
    }
    
    public function preview($id)
    {
        // Redirect to home page for preview
        return redirect()->route('home');
    }
}


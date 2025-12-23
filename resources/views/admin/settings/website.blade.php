@extends('layouts.admin')

@section('title', 'Website Settings - Admin Panel')
@section('page-title', 'Website Settings')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Website Settings</h2>
        <p class="text-[#717182] font-['poppins']">Manage your website's basic information, SEO, meta tags, and scripts</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Site Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="ri-global-line text-[#FF8FA3] text-xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">Basic Site Information</h3>
                    <p class="text-sm text-[#717182]">Configure your website's basic details</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Title <span class="text-red-600">*</span></label>
                    <input type="text" name="site_title" value="{{ old('site_title', $settings->site_title) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]" 
                           placeholder="My Website">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                    <textarea name="site_description" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]"
                              placeholder="A brief description of your website">{{ old('site_description', $settings->site_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Keywords</label>
                    <input type="text" name="site_keywords" value="{{ old('site_keywords', $settings->site_keywords) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]" 
                           placeholder="keyword1, keyword2, keyword3">
                    <p class="text-xs text-gray-500 mt-1">Separate keywords with commas</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Site Icon</label>
                    <input type="file" name="site_icon" accept="image/*" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                    @if($settings->site_icon)
                        <div class="mt-2">
                            <img src="{{ asset($settings->site_icon) }}" alt="Current Icon" class="h-16 w-16 object-cover rounded border">
                            <p class="text-xs text-gray-500 mt-1">Current icon</p>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <input type="file" name="site_favicon" accept="image/*,.ico" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                    @if($settings->site_favicon)
                        <div class="mt-2">
                            <img src="{{ asset($settings->site_favicon) }}" alt="Current Favicon" class="h-16 w-16 object-cover rounded border">
                            <p class="text-xs text-gray-500 mt-1">Current favicon</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Open Graph Meta Tags -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="ri-share-line text-[#FF8FA3] text-xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">Open Graph (OG) Meta Tags</h3>
                    <p class="text-sm text-[#717182]">Configure how your site appears when shared on social media</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG Title</label>
                    <input type="text" name="og_title" value="{{ old('og_title', $settings->og_title) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG Site Name</label>
                    <input type="text" name="og_site_name" value="{{ old('og_site_name', $settings->og_site_name) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG Type</label>
                    <select name="og_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                        <option value="website" {{ old('og_type', $settings->og_type) == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="article" {{ old('og_type', $settings->og_type) == 'article' ? 'selected' : '' }}>Article</option>
                        <option value="business" {{ old('og_type', $settings->og_type) == 'business' ? 'selected' : '' }}>Business</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG URL</label>
                    <input type="url" name="og_url" value="{{ old('og_url', $settings->og_url) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]"
                           placeholder="https://example.com">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG Description</label>
                    <textarea name="og_description" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">{{ old('og_description', $settings->og_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">OG Image</label>
                    <input type="file" name="og_image" accept="image/*" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                    @if($settings->og_image)
                        <div class="mt-2">
                            <img src="{{ asset($settings->og_image) }}" alt="Current OG Image" class="h-32 w-auto object-cover rounded border">
                            <p class="text-xs text-gray-500 mt-1">Current OG image (recommended: 1200x630px)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Twitter Card Meta Tags -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="ri-twitter-x-line text-[#FF8FA3] text-xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">Twitter Card Meta Tags</h3>
                    <p class="text-sm text-[#717182]">Configure how your site appears when shared on Twitter/X</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Card Type</label>
                    <select name="twitter_card_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                        <option value="summary" {{ old('twitter_card_type', $settings->twitter_card_type) == 'summary' ? 'selected' : '' }}>Summary</option>
                        <option value="summary_large_image" {{ old('twitter_card_type', $settings->twitter_card_type) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Title</label>
                    <input type="text" name="twitter_title" value="{{ old('twitter_title', $settings->twitter_title) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Description</label>
                    <textarea name="twitter_description" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">{{ old('twitter_description', $settings->twitter_description) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter Image</label>
                    <input type="file" name="twitter_image" accept="image/*" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                    @if($settings->twitter_image)
                        <div class="mt-2">
                            <img src="{{ asset($settings->twitter_image) }}" alt="Current Twitter Image" class="h-32 w-auto object-cover rounded border">
                            <p class="text-xs text-gray-500 mt-1">Current Twitter image (recommended: 1200x675px)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Scripts Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="ri-code-s-slash-line text-[#FF8FA3] text-xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-[#0A0A0A]">Custom Scripts & Code</h3>
                    <p class="text-sm text-[#717182]">Add Google Search Console, Analytics, and other scripts</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Header Scripts</label>
                    <p class="text-xs text-gray-500 mb-2">Scripts added in &lt;head&gt; section (e.g., Google Search Console verification, Analytics)</p>
                    <textarea name="header_scripts" rows="8" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] font-mono text-sm"
                              placeholder="<!-- Google Search Console -->
<meta name='google-site-verification' content='your-verification-code' />

<!-- Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>">{{ old('header_scripts', $settings->header_scripts) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Scripts</label>
                    <p class="text-xs text-gray-500 mb-2">Scripts added before &lt;/body&gt; tag</p>
                    <textarea name="footer_scripts" rows="8" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] font-mono text-sm"
                              placeholder="<!-- Custom Footer Scripts -->">{{ old('footer_scripts', $settings->footer_scripts) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom CSS</label>
                    <p class="text-xs text-gray-500 mb-2">Custom CSS styles (wrapped in &lt;style&gt; tags automatically)</p>
                    <textarea name="custom_css" rows="8" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] font-mono text-sm"
                              placeholder="/* Custom CSS */">{{ old('custom_css', $settings->custom_css) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom JavaScript</label>
                    <p class="text-xs text-gray-500 mb-2">Custom JavaScript code (wrapped in &lt;script&gt; tags automatically)</p>
                    <textarea name="custom_js" rows="8" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] font-mono text-sm"
                              placeholder="// Custom JavaScript">{{ old('custom_js', $settings->custom_js) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.settings.general') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors font-semibold">
                Save Website Settings
            </button>
        </div>
    </form>
@endsection


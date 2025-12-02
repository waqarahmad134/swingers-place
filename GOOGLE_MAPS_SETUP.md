# Google Maps Integration Setup Guide

## Overview
Google Maps autocomplete functionality has been integrated into the following pages:
1. **Onboarding Step 3** (`resources/views/pages/onboarding/step3.blade.php`)
   - Home location input with autocomplete
   - Interactive map display with draggable marker
   - Travel location input with autocomplete
   
2. **Admin Onboarding Step 3** (`resources/views/admin/users/onboarding/step3.blade.php`)
   - Same functionality as regular onboarding
   
3. **Profile Edit Page** (`resources/views/pages/profile/edit.blade.php`)
   - Location field with Google Maps autocomplete

## Configuration

### 1. Get Google Maps API Key
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the following APIs:
   - **Maps JavaScript API** (required)
   - **Places API** (required for autocomplete)
   - **Geocoding API** (required for reverse geocoding when dragging marker)
   
   **Important:** Make sure to enable **"Places API"** (the legacy version). If you see "Places API (New)", you can enable that too, but the legacy Places API is also needed.
   
4. Go to "Credentials" and create an API key
5. (Recommended) Restrict the API key to your domain for security

### API Loading Configuration
The code now uses the recommended async loading pattern with `loading=async` parameter and callback functions to prevent performance warnings.

### 2. Add API Key to Environment
Add the following line to your `.env` file:

```env
GOOGLE_MAPS_API_KEY=your-api-key-here
```

### 3. Configuration File
The API key is automatically loaded from `config/services.php`:

```php
'google_maps' => [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
],
```

## Features

### Location Autocomplete
- Type to search for cities (e.g., "lah" will show "Lahore", "Lahaina", etc.)
- Select from dropdown suggestions
- Automatically populates formatted address
- Stores latitude and longitude in hidden fields

### Interactive Map (Onboarding pages only)
- Displays when a location is selected
- Draggable marker to fine-tune location
- Updates address when marker is dragged
- Hidden coordinates are automatically saved

## Technical Details

### Hidden Fields
The following hidden fields are automatically added to store coordinates:
- `home_location_lat` - Latitude
- `home_location_lng` - Longitude

### API Libraries Used
- Google Maps JavaScript API
- Places Library (for autocomplete)

### Browser Support
- All modern browsers
- Graceful degradation if API key is not configured (shows console warning)

## Troubleshooting

### Map not showing / Autocomplete not working
1. Check that `GOOGLE_MAPS_API_KEY` is set in `.env`
2. Verify the API key is valid in Google Cloud Console
3. Ensure Maps JavaScript API and Places API are enabled
4. Check browser console for error messages
5. Verify API key restrictions (if any) allow your domain

### Console Warning: "Google Maps API key is not configured"
- Add `GOOGLE_MAPS_API_KEY` to your `.env` file
- Run `php artisan config:clear` if needed

### Error: "You're calling a legacy API, which is not enabled"
- **This is the most common error!**
- **Solution:** You MUST enable "Places API" (the legacy version) in Google Cloud Console
- **Step-by-step:**
  1. Go to https://console.cloud.google.com/
  2. Select your project
  3. Navigate to "APIs & Services" → "Library"
  4. Search for "Places API" (the one WITHOUT "(New)" in the name)
  5. Click on it and click "ENABLE"
  6. Wait 1-5 minutes for changes to propagate
- **See detailed instructions in:** `ENABLE_GOOGLE_PLACES_API.md`

### Warning about Autocomplete being deprecated
- This is a deprecation notice for new customers (after March 1, 2025)
- The current code still uses `Autocomplete` which works fine for existing projects
- The warning doesn't affect functionality but indicates Google recommends `PlaceAutocompleteElement` for new projects
- For now, the current implementation continues to work and will receive bug fixes

## Notes
- The API key is loaded securely from environment variables
- The implementation works with both light and dark mode
- Maps are optimized for mobile and desktop viewing

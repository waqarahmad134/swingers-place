# How to Fix: "You're calling a legacy API, which is not enabled"

## Quick Fix: Enable the Legacy Places API

Follow these steps to enable the required API in Google Cloud Console:

### Step 1: Go to Google Cloud Console
1. Visit: https://console.cloud.google.com/
2. Make sure you're logged in with the correct Google account
3. Select your project (the one associated with your API key)

### Step 2: Navigate to APIs Library
1. In the left sidebar, click on **"APIs & Services"**
2. Click on **"Library"**

### Step 3: Enable Places API (Legacy)
1. In the search bar, type: **"Places API"**
2. You'll see two options:
   - **"Places API"** (this is the legacy one - enable this)
   - **"Places API (New)"** (you can enable this too for future use)
3. Click on **"Places API"** (the legacy one)
4. Click the blue **"ENABLE"** button

### Step 4: Verify It's Enabled
1. Go back to **"APIs & Services"** → **"Enabled APIs"**
2. You should now see **"Places API"** in the list

### Step 5: Wait a Few Minutes
- Changes can take 1-5 minutes to propagate
- Refresh your page and try again

## Alternative: Also Enable These APIs (Recommended)

While you're in the APIs Library, also enable these for full functionality:

1. **Maps JavaScript API** - Required for displaying maps
2. **Places API** - Required for autocomplete (legacy - enable this one)
3. **Geocoding API** - Required for reverse geocoding (when dragging marker)

## Still Getting Errors?

### Check API Key Permissions
1. Go to **"APIs & Services"** → **"Credentials"**
2. Click on your API key
3. Under **"API restrictions"**, make sure these are selected:
   - ✅ Maps JavaScript API
   - ✅ Places API (the legacy one)
   - ✅ Geocoding API
4. Click **"Save"**

### Check Billing
- Google requires a billing account for Maps APIs (even with free tier)
- Go to **"Billing"** in the left sidebar
- Make sure billing is enabled for your project

## Expected Result

After enabling the Places API, the error should disappear. The autocomplete will work properly and you'll be able to:
- Search for locations
- See suggestions as you type
- Select locations from the dropdown
- See the map display

## Need More Help?

If you're still having issues:
1. Make sure you're using the correct Google Cloud project
2. Verify your API key matches the project
3. Check the browser console for specific error messages
4. Wait 5-10 minutes after enabling APIs for changes to take effect

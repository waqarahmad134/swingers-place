<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class HtaccessController extends Controller
{
    public function index(): View
    {
        $htaccessPath = public_path('.htaccess');
        $content = '';
        
        if (File::exists($htaccessPath)) {
            $content = File::get($htaccessPath);
        }
        
        return view('admin.htaccess.index', compact('content'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $htaccessPath = public_path('.htaccess');
        
        try {
            // Backup existing .htaccess if it exists
            if (File::exists($htaccessPath)) {
                $backupPath = public_path('.htaccess.backup.' . date('Y-m-d_His'));
                File::copy($htaccessPath, $backupPath);
            }
            
            // Write new content
            File::put($htaccessPath, $validated['content']);
            
            return redirect()->route('admin.htaccess.index')
                ->with('success', '.htaccess file updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.htaccess.index')
                ->with('error', 'Failed to update .htaccess file: ' . $e->getMessage());
        }
    }

    public function reset(): RedirectResponse
    {
        $htaccessPath = public_path('.htaccess');
        
        try {
            // Backup existing .htaccess if it exists
            if (File::exists($htaccessPath)) {
                $backupPath = public_path('.htaccess.backup.' . date('Y-m-d_His'));
                File::copy($htaccessPath, $backupPath);
            }
            
            // Default Laravel .htaccess content
            $defaultContent = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Exclude storage directory from rewrite rules
    RewriteCond %{REQUEST_URI} !^/storage/
    
    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HTACCESS;
            
            File::put($htaccessPath, $defaultContent);
            
            return redirect()->route('admin.htaccess.index')
                ->with('success', '.htaccess file reset to default successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.htaccess.index')
                ->with('error', 'Failed to reset .htaccess file: ' . $e->getMessage());
        }
    }
}


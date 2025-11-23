<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class LogController extends Controller
{
    public function index(Request $request): View
    {
        $logsPath = storage_path('logs');
        $search = $request->get('search', '');
        $level = $request->get('level', '');
        
        // Get all log files
        $logFiles = collect(File::files($logsPath))
            ->filter(function ($file) {
                return in_array(strtolower($file->getExtension()), ['log']);
            })
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatBytes($file->getSize()),
                    'modified' => $file->getMTime(),
                    'modified_formatted' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            })
            ->sortByDesc('modified')
            ->values();

        // Get selected file or default to first available or laravel.log
        $selectedFile = $request->get('file');
        if (!$selectedFile || !$logFiles->contains('name', $selectedFile)) {
            $selectedFile = $logFiles->isNotEmpty() ? $logFiles->first()['name'] : 'laravel.log';
        }

        // Get log content
        $logContent = '';
        $lines = new LengthAwarePaginator([], 0, 100, 1);
        $selectedFilePath = $logsPath . DIRECTORY_SEPARATOR . $selectedFile;
        
        if (File::exists($selectedFilePath) && $logFiles->contains('name', $selectedFile)) {
            $logContent = File::get($selectedFilePath);
            
            if (!empty(trim($logContent))) {
                $lines = explode("\n", $logContent);
                
                // Reverse to show latest first
                $lines = array_reverse($lines);
                
                // Remove empty lines
                $lines = array_filter($lines, function ($line) {
                    return !empty(trim($line));
                });
                
                // Re-index array after filtering
                $lines = array_values($lines);
                
                // Apply search filter
                if ($search) {
                    $lines = array_filter($lines, function ($line) use ($search) {
                        return stripos($line, $search) !== false;
                    });
                    $lines = array_values($lines);
                }
                
                // Apply level filter
                if ($level) {
                    $lines = array_filter($lines, function ($line) use ($level) {
                        return stripos($line, '[' . strtoupper($level) . ']') !== false;
                    });
                    $lines = array_values($lines);
                }
                
                // Paginate
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 100;
                $total = count($lines);
                $currentItems = array_slice($lines, ($currentPage - 1) * $perPage, $perPage);
                
                $lines = new LengthAwarePaginator(
                    $currentItems,
                    $total,
                    $perPage,
                    $currentPage,
                    [
                        'path' => $request->url(),
                        'query' => $request->query(),
                    ]
                );
            } else {
                $lines = new LengthAwarePaginator([], 0, 100, 1);
            }
        } else {
            $lines = new LengthAwarePaginator([], 0, 100, 1);
        }

        return view('admin.logs.index', compact('logFiles', 'selectedFile', 'lines', 'search', 'level'));
    }

    public function download(string $filename)
    {
        $logsPath = storage_path('logs');
        $filePath = $logsPath . DIRECTORY_SEPARATOR . $filename;
        
        if (!File::exists($filePath)) {
            abort(404, 'Log file not found');
        }
        
        // Security check - ensure file is in logs directory
        $realPath = realpath($filePath);
        $realLogsPath = realpath($logsPath);
        
        if (!$realPath || strpos($realPath, $realLogsPath) !== 0) {
            abort(403, 'Access denied');
        }
        
        return response()->download($filePath, $filename);
    }

    public function clear(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'string'],
        ]);
        
        $logsPath = storage_path('logs');
        $filePath = $logsPath . DIRECTORY_SEPARATOR . $validated['file'];
        
        // Security check
        if (!File::exists($filePath)) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Log file not found');
        }
        
        $realPath = realpath($filePath);
        $realLogsPath = realpath($logsPath);
        
        if (!$realPath || strpos($realPath, $realLogsPath) !== 0) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Access denied');
        }
        
        try {
            File::put($filePath, '');
            
            return redirect()->route('admin.logs.index', ['file' => $validated['file']])
                ->with('success', 'Log file cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.logs.index', ['file' => $validated['file']])
                ->with('error', 'Failed to clear log file: ' . $e->getMessage());
        }
    }

    public function delete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'string'],
        ]);
        
        $logsPath = storage_path('logs');
        $filePath = $logsPath . DIRECTORY_SEPARATOR . $validated['file'];
        
        // Security check
        if (!File::exists($filePath)) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Log file not found');
        }
        
        $realPath = realpath($filePath);
        $realLogsPath = realpath($logsPath);
        
        if (!$realPath || strpos($realPath, $realLogsPath) !== 0) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Access denied');
        }
        
        // Prevent deletion of laravel.log (main log file)
        if ($validated['file'] === 'laravel.log') {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Cannot delete the main Laravel log file. Use "Clear" instead.');
        }
        
        try {
            File::delete($filePath);
            
            return redirect()->route('admin.logs.index')
                ->with('success', 'Log file deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.logs.index')
                ->with('error', 'Failed to delete log file: ' . $e->getMessage());
        }
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}


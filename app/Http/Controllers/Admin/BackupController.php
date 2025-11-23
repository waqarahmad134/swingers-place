<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use ZipArchive;

class BackupController extends Controller
{
    public function index(): View
    {
        $backupsDir = storage_path('app/backups');
        
        // Get all backup files from the filesystem
        $backups = collect([]);
        if (File::exists($backupsDir)) {
            $files = File::files($backupsDir);
            
            $backups = collect($files)
                ->filter(function ($file) {
                    // Only include .zip files
                    return strtolower($file->getExtension()) === 'zip';
                })
                ->map(function ($file) {
                    $name = $file->getFilename();
                    $size = $file->getSize();
                    $createdAt = $file->getMTime();
                    
                    return [
                        'name' => $name,
                        'type' => strpos($name, 'complete') !== false ? 'complete' : 'files',
                        'size' => $this->formatBytes($size),
                        'created_at' => date('Y-m-d H:i:s', $createdAt),
                    ];
                })
                ->sortByDesc(function ($backup) {
                    return $backup['created_at'];
                })
                ->values();
        }

        return view('admin.backup.index', compact('backups'));
    }
    
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function createFilesBackup(Request $request): JsonResponse
    {
        if (!extension_loaded('zip')) {
            return response()->json([
                'success' => false,
                'message' => 'ZipArchive extension is not enabled. Please enable it in php.ini'
            ], 400);
        }

        try {
            $backupName = 'files_backup_' . date('Y-m-d_His') . '.zip';
            $backupPath = storage_path('app/backups/' . $backupName);
            
            // Ensure backups directory exists
            $backupsDir = storage_path('app/backups');
            if (!File::exists($backupsDir)) {
                File::makeDirectory($backupsDir, 0755, true);
            }
            
            $zip = new ZipArchive();
            if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create backup file'
                ], 500);
            }
            
            // Backup storage/app/public directory (media files, etc.)
            $publicStoragePath = storage_path('app/public');
            if (File::exists($publicStoragePath)) {
                $this->addDirectoryToZip($zip, $publicStoragePath, 'storage/public');
            }
            
            // Backup config files
            $configPath = config_path();
            if (File::exists($configPath)) {
                $this->addDirectoryToZip($zip, $configPath, 'config');
            }
            
            // Backup .env file if it exists
            $envPath = base_path('.env');
            if (File::exists($envPath)) {
                $zip->addFile($envPath, '.env');
            }
            
            $zip->close();
            
            return response()->json([
                'success' => true,
                'message' => 'Files backup created successfully',
                'filename' => $backupName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating backup: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function addDirectoryToZip(ZipArchive $zip, string $dir, string $zipPath = '', array $excludeDirs = []): int
    {
        $files = File::allFiles($dir);
        $addedCount = 0;
        
        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = $zipPath . '/' . $file->getRelativePathname();
            
            // Skip hidden files
            if (strpos($file->getFilename(), '.') === 0) {
                continue;
            }
            
            // Check if file is in excluded directory
            $shouldExclude = false;
            foreach ($excludeDirs as $excludeDir) {
                if (strpos($filePath, $excludeDir) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if (!$shouldExclude) {
                // Use addFileFromString for better Windows compatibility
                if (is_readable($filePath)) {
                    if ($zip->addFile($filePath, $relativePath)) {
                        $addedCount++;
                    } else {
                        Log::warning('Failed to add file to ZIP: ' . $filePath);
                    }
                } else {
                    Log::warning('File is not readable: ' . $filePath);
                }
            }
        }
        
        return $addedCount;
    }

    public function downloadCompleteProject()
    {
        Log::info('Starting complete project backup');
        
        if (!extension_loaded('zip')) {
            Log::error('ZipArchive extension not loaded');
            return redirect()->route('admin.backup.index')
                ->with('error', 'ZipArchive extension is not enabled. Please enable it in php.ini');
        }

        try {
            $backupName = 'complete_project_' . date('Y-m-d_His') . '.zip';
            Log::info('Backup name: ' . $backupName);
            
            // Ensure backups directory exists with proper permissions
            $backupsDir = storage_path('app/backups');
            Log::info('Backups directory: ' . $backupsDir);
            
            if (!File::exists($backupsDir)) {
                Log::info('Creating backups directory');
                File::makeDirectory($backupsDir, 0755, true);
            }
            
            // Check if directory is writable
            $isWritable = is_writable($backupsDir);
            Log::info('Backups directory writable: ' . ($isWritable ? 'yes' : 'no'));
            
            if (!$isWritable) {
                // Try to make it writable
                Log::info('Attempting to chmod backups directory');
                @chmod($backupsDir, 0755);
                $isWritable = is_writable($backupsDir);
                Log::info('After chmod, writable: ' . ($isWritable ? 'yes' : 'no'));
                
                if (!$isWritable) {
                    Log::error('Backups directory is not writable: ' . $backupsDir);
                    return redirect()->route('admin.backup.index')
                        ->with('error', 'Backups directory is not writable. Please check permissions on: ' . $backupsDir);
                }
            }
            
            // Use system temp directory to avoid Windows permission issues
            $backupDir = str_replace('/', DIRECTORY_SEPARATOR, storage_path('app/backups'));
            $systemTempDir = sys_get_temp_dir();
            $tempZipPath = $systemTempDir . DIRECTORY_SEPARATOR . uniqid('backup_', true) . '.zip';
            $backupPath = $backupDir . DIRECTORY_SEPARATOR . $backupName;
            
            // If target file exists, delete it first
            if (File::exists($backupPath)) {
                Log::info('Deleting existing backup file');
                @unlink($backupPath);
            }
            
            // Delete any existing temp file
            if (File::exists($tempZipPath)) {
                @unlink($tempZipPath);
            }
            
            Log::info('System temp directory: ' . $systemTempDir);
            Log::info('Temp ZIP path: ' . $tempZipPath);
            Log::info('Final backup path: ' . $backupPath);
            Log::info('System temp writable: ' . (is_writable($systemTempDir) ? 'yes' : 'no'));
            
            // Ensure the backup directory exists
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }
            
            $zip = new ZipArchive();
            Log::info('Opening ZIP archive at: ' . $tempZipPath);
            $result = $zip->open($tempZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            Log::info('ZIP open result: ' . $result);
            
            if ($result !== true) {
                $errorMsg = 'Failed to create backup file';
                switch ($result) {
                    case ZipArchive::ER_EXISTS:
                        $errorMsg = 'File already exists';
                        break;
                    case ZipArchive::ER_INCONS:
                        $errorMsg = 'Zip archive inconsistent';
                        break;
                    case ZipArchive::ER_INVAL:
                        $errorMsg = 'Invalid argument';
                        break;
                    case ZipArchive::ER_MEMORY:
                        $errorMsg = 'Memory allocation failure';
                        break;
                    case ZipArchive::ER_NOZIP:
                        $errorMsg = 'Not a zip archive';
                        break;
                    case ZipArchive::ER_OPEN:
                        $errorMsg = 'Can\'t open file';
                        break;
                    case ZipArchive::ER_READ:
                        $errorMsg = 'Read error';
                        break;
                    case ZipArchive::ER_SEEK:
                        $errorMsg = 'Seek error';
                        break;
                }
                Log::error('ZIP open failed: ' . $errorMsg . ' (Code: ' . $result . ')');
                return redirect()->route('admin.backup.index')
                    ->with('error', $errorMsg . ' (Error code: ' . $result . ')');
            }
            
            Log::info('ZIP archive opened successfully');
            
            $basePath = base_path();
            
            // Directories to exclude from backup
            $excludeDirs = [
                'vendor',
                'node_modules',
                '.git',
                '.idea',
                '.vscode',
                'storage/logs',
                'storage/framework/cache',
                'storage/framework/sessions',
                'storage/framework/views',
            ];
            
            // Add application code directories
            $directoriesToBackup = [
                'app',
                'bootstrap',
                'config',
                'database',
                'public',
                'resources',
                'routes',
            ];
            
            $filesAdded = 0;
            foreach ($directoriesToBackup as $dir) {
                $dirPath = $basePath . '/' . $dir;
                if (File::exists($dirPath)) {
                    Log::info('Adding directory to ZIP: ' . $dir);
                    $count = $this->addDirectoryToZip($zip, $dirPath, $dir, $excludeDirs);
                    $filesAdded += $count;
                    Log::info('Added ' . $count . ' files from ' . $dir);
                }
            }
            
            // Add composer.json and package.json if they exist
            $filesToBackup = [
                'composer.json',
                'composer.lock',
                'package.json',
                'package-lock.json',
                'artisan',
                '.env.example',
            ];
            
            foreach ($filesToBackup as $file) {
                $filePath = $basePath . '/' . $file;
                if (File::exists($filePath)) {
                    if ($zip->addFile($filePath, $file)) {
                        $filesAdded++;
                    } else {
                        Log::warning('Failed to add file: ' . $file);
                    }
                }
            }
            
            // Add .env file (if exists)
            $envPath = $basePath . '/.env';
            if (File::exists($envPath)) {
                if ($zip->addFile($envPath, '.env')) {
                    $filesAdded++;
                } else {
                    Log::warning('Failed to add .env file');
                }
            }
            
            // Add storage/app/public (media files)
            $publicStoragePath = storage_path('app/public');
            if (File::exists($publicStoragePath)) {
                Log::info('Adding storage/app/public directory');
                $count = $this->addDirectoryToZip($zip, $publicStoragePath, 'storage/app/public', $excludeDirs);
                $filesAdded += $count;
                Log::info('Added ' . $count . ' files from storage/app/public');
            }
            
            Log::info('Total files added to ZIP: ' . $filesAdded);
            
            // Generate and add database dump
            Log::info('Generating database dump');
            $databaseDump = $this->generateDatabaseDump();
            if ($databaseDump) {
                Log::info('Database dump generated, size: ' . strlen($databaseDump) . ' bytes');
                $zip->addFromString('database_dump.sql', $databaseDump);
            } else {
                Log::warning('Database dump generation failed or returned null');
            }
            
            Log::info('Closing ZIP archive');
            
            // On Windows, ZipArchive::close() can fail even when the file is created
            // We'll suppress the error and check if the file was actually created
            $closeResult = false;
            $closeError = null;
            $zipObject = $zip; // Store reference before we might lose it
            
            try {
                // Suppress the error warning
                set_error_handler(function($errno, $errstr) use (&$closeError) {
                    if (strpos($errstr, 'ZipArchive') !== false || strpos($errstr, 'Invalid or uninitialized') !== false) {
                        $closeError = $errstr;
                        return true; // Suppress the error
                    }
                    return false; // Let other errors through
                }, E_WARNING | E_NOTICE);
                
                // Check if zip object is still valid before closing
                if ($zipObject && is_object($zipObject)) {
                    $closeResult = @$zipObject->close();
                } else {
                    Log::warning('ZipArchive object is invalid before close');
                    $closeError = 'ZipArchive object is invalid';
                }
                
                restore_error_handler();
            } catch (\Throwable $e) {
                restore_error_handler();
                $closeError = $e->getMessage();
                Log::warning('Exception during ZIP close: ' . $closeError);
            }
            
            // Unset zip object to release handle immediately
            unset($zip, $zipObject);
            gc_collect_cycles();
            
            // Wait a moment for Windows to release the file
            usleep(500000); // 0.5 second
            
            Log::info('ZIP close result: ' . ($closeResult ? 'true' : 'false'));
            if ($closeError) {
                Log::info('ZIP close error (may be false positive): ' . $closeError);
            }
            
            // Wait a bit longer for Windows to release the file handle
            usleep(1000000); // 1 second
            
            // Check if temp file was created (Windows sometimes reports error but file is valid)
            if (File::exists($tempZipPath)) {
                $fileSize = File::size($tempZipPath);
                Log::info('Temp ZIP file exists, size: ' . $fileSize . ' bytes');
                
                if ($fileSize > 0) {
                    // File exists and has content, move it to final location
                    Log::info('Moving temp file to final location');
                    
                    // Ensure destination directory exists and is writable
                    if (!is_writable($backupDir)) {
                        Log::error('Backup directory is not writable: ' . $backupDir);
                        @unlink($tempZipPath);
                        return redirect()->route('admin.backup.index')
                            ->with('error', 'Backup directory is not writable. Please check permissions.');
                    }
                    
                    // Try copy first (more reliable on Windows), then delete temp
                    if (@copy($tempZipPath, $backupPath)) {
                        Log::info('File copied successfully');
                        @unlink($tempZipPath);
                        
                        // Verify final file
                        if (File::exists($backupPath) && File::size($backupPath) > 0) {
                            Log::info('Backup file created successfully (size: ' . File::size($backupPath) . ' bytes)');
                        } else {
                            Log::error('Final backup file is missing or empty after copy');
                            return redirect()->route('admin.backup.index')
                                ->with('error', 'Backup file was created but is invalid. Please check disk space.');
                        }
                    } elseif (@rename($tempZipPath, $backupPath)) {
                        // If copy fails, try rename (might work if on same filesystem)
                        Log::info('File renamed successfully');
                        
                        // Verify final file
                        if (File::exists($backupPath) && File::size($backupPath) > 0) {
                            Log::info('Backup file created successfully (size: ' . File::size($backupPath) . ' bytes)');
                        } else {
                            Log::error('Final backup file is missing or empty after rename');
                            return redirect()->route('admin.backup.index')
                                ->with('error', 'Backup file was created but is invalid. Please check disk space.');
                        }
                    } else {
                        $copyError = error_get_last();
                        Log::error('Failed to copy/rename temp file: ' . ($copyError ? $copyError['message'] : 'Unknown error'));
                        Log::error('Temp file readable: ' . (is_readable($tempZipPath) ? 'yes' : 'no'));
                        Log::error('Destination writable: ' . (is_writable($backupDir) ? 'yes' : 'no'));
                        @unlink($tempZipPath);
                        return redirect()->route('admin.backup.index')
                            ->with('error', 'Failed to move backup file to final location. Please check permissions.');
                    }
                } else {
                    Log::error('Temp ZIP file exists but is empty');
                    @unlink($tempZipPath);
                    return redirect()->route('admin.backup.index')
                        ->with('error', 'Backup file was created but is empty. Please check disk space and permissions.');
                }
            } else {
                Log::error('Temp ZIP file does not exist after close');
                Log::error('Temp path: ' . $tempZipPath);
                Log::error('Final path: ' . $backupPath);
                Log::error('System temp writable: ' . (is_writable($systemTempDir) ? 'yes' : 'no'));
                Log::error('Backup dir writable: ' . (is_writable($backupDir) ? 'yes' : 'no'));
                
                // Check if there are any files in system temp that match our pattern
                $tempFiles = glob($systemTempDir . DIRECTORY_SEPARATOR . 'backup_*.zip');
                Log::info('Found temp files matching pattern: ' . count($tempFiles));
                
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Failed to create backup file. ZipArchive::close() failed. This may be a Windows permission issue. Check logs for details.');
            }
            
            Log::info('Starting file download');
            // Download the file
            return response()->download($backupPath, $backupName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Exception during backup: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.backup.index')
                ->with('error', 'Error creating complete project backup: ' . $e->getMessage() . '. Check logs for details.');
        }
    }
    
    private function generateDatabaseDump(): ?string
    {
        try {
            $databaseName = DB::connection()->getDatabaseName();
            $sql = "-- Database Dump\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sql .= "-- Database: {$databaseName}\n\n";
            $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $sql .= "SET time_zone = \"+00:00\";\n\n";
            
            $tables = DB::select('SHOW TABLES');
            
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                
                // Drop table
                $sql .= "\n-- --------------------------------------------------------\n\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n\n";
                
                // Create table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = $createTable[0]->{'Create Table'};
                $sql .= $createTableSql . ";\n\n";
                
                // Insert data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $columns = Schema::getColumnListing($tableName);
                    $sql .= "LOCK TABLES `{$tableName}` WRITE;\n";
                    $sql .= "/*!40000 ALTER TABLE `{$tableName}` DISABLE KEYS */;\n\n";
                    
                    $pdo = DB::getPdo();
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ($columns as $column) {
                            $value = $row->$column;
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                // Use PDO quote for proper escaping
                                $values[] = $pdo->quote($value);
                            }
                        }
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    
                    $sql .= "\n/*!40000 ALTER TABLE `{$tableName}` ENABLE KEYS */;\n";
                    $sql .= "UNLOCK TABLES;\n\n";
                }
            }
            
            return $sql;
        } catch (\Exception $e) {
            // If we can't generate database dump, continue without it
            return null;
        }
    }

    public function downloadFilesBackup(string $filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }

    public function deleteBackup(string $filename): JsonResponse
    {
        try {
            $filePath = storage_path('app/backups/' . $filename);
            
            if (File::exists($filePath)) {
                File::delete($filePath);
                return response()->json([
                    'success' => true,
                    'message' => 'Backup deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Backup file not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting backup: ' . $e->getMessage()
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class DatabaseController extends Controller
{
    public function index(): View
    {
        $databaseName = DB::connection()->getDatabaseName();
        
        $tableInfo = collect(DB::select('SHOW TABLES'))
            ->map(function ($table) use ($databaseName) {
                $tableName = array_values((array) $table)[0];
                
                // Get row count
                $rows = DB::table($tableName)->count();
                
                // Get columns
                $columns = Schema::getColumnListing($tableName);
                
                // Get table size (MySQL specific)
                $sizeResult = DB::select("
                    SELECT 
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = ? 
                    AND table_name = ?
                ", [$databaseName, $tableName]);
                
                $size = isset($sizeResult[0]) && isset($sizeResult[0]->size_mb) 
                    ? number_format($sizeResult[0]->size_mb, 2) . ' MB'
                    : 'N/A';
                
                return [
                    'name' => $tableName,
                    'rows' => $rows,
                    'size' => $size,
                    'columns' => $columns,
                ];
            });

        return view('admin.database.index', compact('tableInfo'));
    }

    public function show(string $table): View
    {
        if (!Schema::hasTable($table)) {
            abort(404);
        }

        $columns = Schema::getColumnListing($table);
        $data = DB::table($table)->paginate(50);

        return view('admin.database.show', compact('table', 'columns', 'data'));
    }

    public function download()
    {
        $databaseName = DB::connection()->getDatabaseName();
        $config = Config::get('database.connections.mysql');
        
        $filename = $databaseName . '_' . date('Y-m-d_His') . '.sql';
        $tempFile = sys_get_temp_dir() . '/' . $filename;
        
        // Try to use mysqldump if available
        $mysqldumpPath = $this->findMysqldump();
        
        if ($mysqldumpPath) {
            // Use mysqldump command
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --port=%s %s > "%s"',
                $mysqldumpPath,
                escapeshellarg($config['username']),
                escapeshellarg($config['password']),
                escapeshellarg($config['host']),
                escapeshellarg($config['port'] ?? 3306),
                escapeshellarg($databaseName),
                $tempFile
            );
            
            exec($command . ' 2>&1', $output, $returnVar);
            
            if ($returnVar === 0 && file_exists($tempFile)) {
                return response()->download($tempFile, $filename, [
                    'Content-Type' => 'application/sql',
                ])->deleteFileAfterSend(true);
            }
        }
        
        // Fallback: Generate SQL manually
        $sql = $this->generateSqlDump();
        
        return response($sql, 200, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    private function findMysqldump(): ?string
    {
        // Check if mysqldump is in PATH
        $which = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
        $result = shell_exec($which . ' mysqldump 2>&1');
        if ($result && !empty(trim($result)) && strpos($result, 'not found') === false) {
            return 'mysqldump';
        }
        
        // Check common paths
        $paths = [];
        if (PHP_OS_FAMILY === 'Windows') {
            $paths = [
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                'C:\\Program Files (x86)\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files (x86)\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
            ];
        } else {
            $paths = [
                '/usr/bin/mysqldump',
                '/usr/local/bin/mysqldump',
                '/opt/local/bin/mysqldump',
            ];
        }
        
        foreach ($paths as $path) {
            if (file_exists($path) && is_readable($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    private function generateSqlDump(): string
    {
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
    }

    public function clear(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'confirm' => 'required|accepted',
            ]);

            // Clear database tables (be careful!)
            // This is a dangerous operation
            return redirect()->route('admin.database.index')
                ->with('success', 'Database cleared (functionality disabled for safety)');
        }

        return view('admin.database.clear');
    }
}


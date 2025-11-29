<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DeploymentController extends Controller
{
    /**
     * Execute deployment commands
     */
    public function deploy(Request $request)
    {
        $commands = [];
        $output = [];
        $errors = [];

        // Get commands to execute from request or execute all
        $action = $request->input('action', 'all');

        try {
            // Composer Install
            if ($action === 'all' || $action === 'composer') {
                $commands[] = 'composer install --no-interaction --prefer-dist --optimize-autoloader';
                $result = $this->runCommand('composer install --no-interaction --prefer-dist --optimize-autoloader');
                $output[] = ['command' => 'composer install', 'result' => $result];
            }

            // Run Migrations
            if ($action === 'all' || $action === 'migrate') {
                $result = $this->runArtisan('migrate', ['--force' => true]);
                $output[] = ['command' => 'php artisan migrate', 'result' => $result];
            }

            // Seed Database
            if ($action === 'all' || $action === 'seed') {
                $result = $this->runArtisan('db:seed', ['--force' => true]);
                $output[] = ['command' => 'php artisan db:seed', 'result' => $result];
            }

            // Optimize
            if ($action === 'all' || $action === 'optimize') {
                $result = $this->runArtisan('optimize');
                $output[] = ['command' => 'php artisan optimize', 'result' => $result];
            }

            // Clear config cache (always run after optimize)
            if ($action === 'all' || $action === 'optimize') {
                $this->runArtisan('config:clear');
            }

            return response()->json([
                'success' => true,
                'message' => 'Deployment completed successfully',
                'output' => $output,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Deployment failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Deployment failed: ' . $e->getMessage(),
                'output' => $output,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Find PHP executable
     */
    private function findPhp(): string
    {
        // First, check if PHP path is set in .env
        $envPhp = env('PHP_PATH');
        if ($envPhp && file_exists($envPhp)) {
            return escapeshellarg($envPhp);
        }

        // Use current PHP binary (since we're running in PHP)
        $phpBinary = PHP_BINARY;
        if ($phpBinary && file_exists($phpBinary)) {
            return escapeshellarg($phpBinary);
        }

        // Try to find PHP in PATH (Windows)
        if (PHP_OS_FAMILY === 'Windows') {
            $whereCommands = [
                'where php 2>nul',
                'where php.exe 2>nul',
            ];
            
            foreach ($whereCommands as $whereCmd) {
                $process = Process::fromShellCommandline($whereCmd);
                $process->run();
                if ($process->isSuccessful()) {
                    $output = trim($process->getOutput());
                    if (!empty($output)) {
                        $lines = explode("\n", $output);
                        $phpPath = trim($lines[0]);
                        if (file_exists($phpPath)) {
                            return escapeshellarg($phpPath);
                        }
                    }
                }
            }
        } else {
            // Linux/Unix: try which php
            $process = Process::fromShellCommandline('which php 2>/dev/null');
            $process->run();
            if ($process->isSuccessful() && trim($process->getOutput())) {
                return 'php';
            }
        }

        // Check common PHP locations (Windows and Linux/Shared Hosting)
        $commonPaths = [];
        
        if (PHP_OS_FAMILY === 'Windows') {
            $commonPaths = [
                'C:\\xampp\\php\\php.exe',
                'C:\\Program Files\\PHP\\php.exe',
                'C:\\Program Files (x86)\\PHP\\php.exe',
                'C:\\wamp\\bin\\php\\php8.2.0\\php.exe',
                'C:\\wamp64\\bin\\php\\php8.2.0\\php.exe',
            ];
        } else {
            // Linux/Shared Hosting common paths
            $commonPaths = [
                '/usr/bin/php',
                '/usr/local/bin/php',
                '/opt/php/bin/php',
                '/opt/cpanel/ea-php82/root/usr/bin/php', // cPanel PHP 8.2
                '/opt/cpanel/ea-php81/root/usr/bin/php', // cPanel PHP 8.1
                '/opt/cpanel/ea-php80/root/usr/bin/php', // cPanel PHP 8.0
                '/usr/bin/php82', // Some shared hosts
                '/usr/bin/php81',
                '/usr/bin/php80',
            ];
        }

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                return escapeshellarg($path);
            }
        }

        // Last resort: try php command anyway (might work if in PATH)
        return 'php';
    }

    /**
     * Find composer executable
     */
    private function findComposer(): string
    {
        $php = $this->findPhp();
        
        // First, check if composer path is set in .env
        $envComposer = env('COMPOSER_PATH');
        if ($envComposer && file_exists($envComposer)) {
            if (strpos($envComposer, '.phar') !== false || strpos($envComposer, '.bat') !== false) {
                return $php . ' ' . escapeshellarg($envComposer);
            }
            return escapeshellarg($envComposer);
        }

        // Check if composer.phar exists in project root
        $composerPhar = base_path('composer.phar');
        if (file_exists($composerPhar)) {
            return $php . ' ' . escapeshellarg($composerPhar);
        }

        // Check parent directory for composer.phar
        $parentComposerPhar = dirname(base_path()) . DIRECTORY_SEPARATOR . 'composer.phar';
        if (file_exists($parentComposerPhar)) {
            return $php . ' ' . escapeshellarg($parentComposerPhar);
        }

        // Try to find composer in PATH (Windows)
        if (PHP_OS_FAMILY === 'Windows') {
            $whereCommands = [
                'where composer 2>nul',
                'where composer.bat 2>nul',
            ];
            
            foreach ($whereCommands as $whereCmd) {
                $process = Process::fromShellCommandline($whereCmd);
                $process->run();
                if ($process->isSuccessful()) {
                    $output = trim($process->getOutput());
                    if (!empty($output)) {
                        $lines = explode("\n", $output);
                        $composerPath = trim($lines[0]);
                        if (file_exists($composerPath)) {
                            return escapeshellarg($composerPath);
                        }
                    }
                }
            }
        } else {
            // Linux/Unix: try which composer
            $process = Process::fromShellCommandline('which composer 2>/dev/null');
            $process->run();
            if ($process->isSuccessful() && trim($process->getOutput())) {
                return 'composer';
            }
        }

        // Check common composer locations (Windows and Linux/Shared Hosting)
        $commonPaths = [
            base_path('composer.phar'),
            base_path('../composer.phar'),
        ];
        
        if (PHP_OS_FAMILY === 'Windows') {
            $commonPaths = array_merge($commonPaths, [
                'C:\\ProgramData\\ComposerSetup\\bin\\composer.bat',
                'C:\\composer\\composer.bat',
                'C:\\Users\\' . get_current_user() . '\\AppData\\Roaming\\Composer\\vendor\\bin\\composer.bat',
            ]);
        } else {
            // Linux/Shared Hosting common paths
            $commonPaths = array_merge($commonPaths, [
                '/usr/local/bin/composer',
                '/usr/bin/composer',
                '/opt/cpanel/composer/bin/composer', // cPanel composer
                base_path('vendor/bin/composer'), // Project-local composer
                getenv('HOME') . '/.composer/vendor/bin/composer', // User composer
                getenv('HOME') . '/.config/composer/vendor/bin/composer',
            ]);
        }

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                if (strpos($path, '.phar') !== false || strpos($path, '.bat') !== false) {
                    return $php . ' ' . escapeshellarg($path);
                }
                return escapeshellarg($path);
            }
        }

        // Last resort: try composer command anyway (might work if in PATH)
        return 'composer';
    }

    /**
     * Run a shell command
     */
    private function runCommand(string $command): array
    {
        // If it's a composer command, use findComposer
        if (strpos($command, 'composer') === 0) {
            $composer = $this->findComposer();
            $command = str_replace('composer', $composer, $command);
        }

        $process = Process::fromShellCommandline($command, base_path());
        $process->setTimeout(300); // 5 minutes timeout
        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exit_code' => $process->getExitCode(),
        ];
    }

    /**
     * Run an Artisan command
     */
    private function runArtisan(string $command, array $options = []): array
    {
        try {
            Artisan::call($command, $options);
            $output = Artisan::output();

            return [
                'success' => true,
                'output' => $output,
                'error' => '',
                'exit_code' => 0,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'output' => '',
                'error' => $e->getMessage(),
                'exit_code' => 1,
            ];
        }
    }

    /**
     * Show deployment page
     */
    public function index()
    {
        return view('admin.deployment.index');
    }

    /**
     * Public deployment endpoint (with token authentication)
     * Usage: POST /deploy/{token}?action=all
     * Token should be set in .env as DEPLOYMENT_TOKEN
     */
    public function deployPublic(Request $request, string $token)
    {
        // Verify token
        $validToken = env('DEPLOYMENT_TOKEN', 'waqarahmad134');
        
        if ($token !== $validToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid deployment token',
            ], 401);
        }

        // Use the same deploy logic
        return $this->deploy($request);
    }
}


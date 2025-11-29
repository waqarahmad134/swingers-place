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
     * Run a shell command
     */
    private function runCommand(string $command): array
    {
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
        $validToken = env('DEPLOYMENT_TOKEN', 'change-this-token-in-env-file');
        
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


@extends('layouts.admin')

@section('title', 'Deployment - Admin Panel')
@section('page-title', 'Deployment')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Deployment</h2>
        <p class="text-[#717182] font-['poppins']">Execute deployment commands without SSH access</p>
    </div>

    <!-- Deployment Actions -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <i class="ri-rocket-line text-[#FF8FA3] text-xl"></i>
            <div>
                <h3 class="text-lg font-semibold text-[#0A0A0A]">Deployment Commands</h3>
                <p class="text-sm text-[#717182]">Run deployment tasks with a single click</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <button onclick="runDeployment('composer')" class="deploy-btn p-4 border-2 border-gray-200 rounded-xl hover:border-[#FF8FA3] transition-all text-left">
                <div class="flex items-center gap-3 mb-2">
                    <i class="ri-code-s-slash-line text-2xl text-[#FF8FA3]"></i>
                    <h4 class="font-semibold text-gray-900">Composer Install</h4>
                </div>
                <p class="text-sm text-gray-600">Install dependencies</p>
            </button>

            <button onclick="runDeployment('migrate')" class="deploy-btn p-4 border-2 border-gray-200 rounded-xl hover:border-[#FF8FA3] transition-all text-left">
                <div class="flex items-center gap-3 mb-2">
                    <i class="ri-database-2-line text-2xl text-[#FF8FA3]"></i>
                    <h4 class="font-semibold text-gray-900">Run Migrations</h4>
                </div>
                <p class="text-sm text-gray-600">Update database schema</p>
            </button>

            <button onclick="runDeployment('seed')" class="deploy-btn p-4 border-2 border-gray-200 rounded-xl hover:border-[#FF8FA3] transition-all text-left">
                <div class="flex items-center gap-3 mb-2">
                    <i class="ri-seedling-line text-2xl text-[#FF8FA3]"></i>
                    <h4 class="font-semibold text-gray-900">Seed Database</h4>
                </div>
                <p class="text-sm text-gray-600">Populate database</p>
            </button>

            <button onclick="runDeployment('optimize')" class="deploy-btn p-4 border-2 border-gray-200 rounded-xl hover:border-[#FF8FA3] transition-all text-left">
                <div class="flex items-center gap-3 mb-2">
                    <i class="ri-speed-up-line text-2xl text-[#FF8FA3]"></i>
                    <h4 class="font-semibold text-gray-900">Optimize</h4>
                </div>
                <p class="text-sm text-gray-600">Cache & optimize</p>
            </button>
        </div>

        <!-- Full Deployment -->
        <div class="border-t border-gray-200 pt-6">
            <button onclick="runDeployment('all')" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-[#FF8FA3] to-[#9810FA] text-white rounded-lg hover:opacity-90 transition-all font-semibold flex items-center justify-center gap-2">
                <i class="ri-play-circle-line text-xl"></i>
                <span>Run Full Deployment</span>
            </button>
            <p class="text-sm text-gray-600 mt-2">Executes: Composer Install → Migrate → Seed → Optimize</p>
        </div>
    </div>

    <!-- Output Display -->
    <div id="output-container" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[#0A0A0A]">Deployment Output</h3>
            <button onclick="clearOutput()" class="text-gray-500 hover:text-gray-700">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <div id="output" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-auto max-h-96"></div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#FF8FA3] mx-auto mb-4"></div>
            <p class="text-gray-700 font-semibold">Running deployment...</p>
            <p class="text-sm text-gray-500 mt-2">Please wait, this may take a few minutes</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    async function runDeployment(action) {
        const loadingOverlay = document.getElementById('loading-overlay');
        const outputContainer = document.getElementById('output-container');
        const output = document.getElementById('output');
        
        // Show loading
        loadingOverlay.classList.remove('hidden');
        outputContainer.classList.remove('hidden');
        output.innerHTML = '<div class="text-yellow-400">Starting deployment...</div>';

        try {
            const response = await fetch('{{ route("admin.deployment.deploy") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action })
            });

            const data = await response.json();
            
            if (data.success) {
                let outputHtml = '<div class="text-green-400 mb-4">✓ Deployment completed successfully!</div>';
                
                if (data.output && data.output.length > 0) {
                    data.output.forEach(cmd => {
                        outputHtml += `<div class="mb-3 border-b border-gray-700 pb-2">`;
                        outputHtml += `<div class="text-blue-400 font-semibold mb-1">${cmd.command}</div>`;
                        if (cmd.result.success) {
                            outputHtml += `<div class="text-green-400 whitespace-pre-wrap">${cmd.result.output || 'Command executed successfully'}</div>`;
                        } else {
                            outputHtml += `<div class="text-red-400 whitespace-pre-wrap">${cmd.result.error || 'Command failed'}</div>`;
                        }
                        outputHtml += `</div>`;
                    });
                }
                
                output.innerHTML = outputHtml;
            } else {
                output.innerHTML = `<div class="text-red-400">✗ Deployment failed: ${data.message || data.error}</div>`;
            }
        } catch (error) {
            output.innerHTML = `<div class="text-red-400">✗ Error: ${error.message}</div>`;
        } finally {
            loadingOverlay.classList.add('hidden');
            // Scroll to output
            output.scrollTop = output.scrollHeight;
        }
    }

    function clearOutput() {
        document.getElementById('output-container').classList.add('hidden');
        document.getElementById('output').innerHTML = '';
    }
</script>
@endpush


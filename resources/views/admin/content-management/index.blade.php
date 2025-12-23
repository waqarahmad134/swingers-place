@extends('layouts.admin')

@section('title', 'Content Management - Admin Panel')
@section('page-title', 'Content & Text Management')

@section('content')
    <section class="content">
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Content & Text Management</h2>
        <p class="text-[#717182] font-['poppins']">Edit text content displayed on the homepage.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        @foreach($groupedSections as $groupName => $sections)
            @if($sections->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-[#0A0A0A] mb-4 pb-2 border-b border-gray-200">
                        {{ $groupName }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sections as $section)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-[#0A0A0A]">{{ $section->section_name }}</h4>
                                    @if(!$section->is_active)
                                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">Inactive</span>
                                    @endif
                                </div>
                                
                                <p class="text-xs text-[#717182] mb-3 line-clamp-2">{{ \Illuminate\Support\Str::limit($section->content, 80) }}</p>
                                
                                <div class="flex gap-2">
                                    <button 
                                       data-section-id="{{ $section->id }}"
                                       data-section-name="{{ htmlspecialchars($section->section_name, ENT_QUOTES, 'UTF-8') }}"
                                       data-section-key="{{ htmlspecialchars($section->section_key, ENT_QUOTES, 'UTF-8') }}"
                                       data-section-content="{{ htmlspecialchars($section->content, ENT_QUOTES, 'UTF-8') }}"
                                       data-section-active="{{ $section->is_active ? '1' : '0' }}"
                                       onclick="openEditModalFromButton(this)" 
                                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-[#FF8FA3] rounded-lg text-sm font-medium text-white hover:bg-[#FF7A91] transition-colors">
                                        <i class="ri-file-edit-line"></i>
                                        Edit
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Edit Content Modal -->
    <div id="editContentModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Edit Content</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <form id="editContentForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Name</label>
                        <input type="text" id="modal_section_name" readonly 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section Key</label>
                        <input type="text" id="modal_section_key" readonly 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 dark:bg-gray-700 dark:text-gray-400 text-xs">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content <span class="text-red-600">*</span></label>
                        <textarea name="content" id="modal_content" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('content') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="modal_is_active" value="1" 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors font-semibold">
                        Update Content
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModalFromButton(button) {
            const id = button.getAttribute('data-section-id');
            const sectionName = button.getAttribute('data-section-name');
            const sectionKey = button.getAttribute('data-section-key');
            const content = button.getAttribute('data-section-content');
            const isActive = button.getAttribute('data-section-active') === '1';
            
            openEditModal(id, sectionName, sectionKey, content, isActive);
        }
        
        function openEditModal(id, sectionName, sectionKey, content, isActive) {
            const modal = document.getElementById('editContentModal');
            const form = document.getElementById('editContentForm');
            const sectionNameInput = document.getElementById('modal_section_name');
            const sectionKeyInput = document.getElementById('modal_section_key');
            const contentTextarea = document.getElementById('modal_content');
            const isActiveCheckbox = document.getElementById('modal_is_active');
            
            // Set form action
            form.action = '{{ route("admin.content-management.update", ":id") }}'.replace(':id', id);
            
            // Populate form fields
            sectionNameInput.value = sectionName || '';
            sectionKeyInput.value = sectionKey || '';
            contentTextarea.value = content || '';
            isActiveCheckbox.checked = isActive;
            
            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Focus on textarea
            setTimeout(() => contentTextarea.focus(), 100);
        }
        
        function closeEditModal() {
            const modal = document.getElementById('editContentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            
            // Reset form
            document.getElementById('editContentForm').reset();
        }
        
        // Handle form submission
        document.getElementById('editContentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showSuccessMessage('Content updated successfully!');
                    
                    // Close modal
                    closeEditModal();
                    
                    // Reload page after a short delay to show updated content
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to update content'));
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating content. Please try again.');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
        
        function showSuccessMessage(message) {
            // Remove existing success messages
            const existingMessages = document.querySelectorAll('.success-message');
            existingMessages.forEach(msg => msg.remove());
            
            // Create success message element
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300';
            successDiv.textContent = message;
            
            // Insert at the top of content area
            const contentArea = document.querySelector('section.content');
            if (contentArea) {
                contentArea.insertBefore(successDiv, contentArea.firstChild);
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('editContentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
        
        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('editContentModal');
                if (!modal.classList.contains('hidden')) {
                    closeEditModal();
                }
            }
        });
    </script>
    @endpush
@endsection

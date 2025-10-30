// Modal CRUD Operations
class CrudModal {
    constructor() {
        this.init();
    }

    init() {
        // Add event listeners when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupEventListeners());
        } else {
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Handle form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('crud-form')) {
                e.preventDefault();
                this.handleFormSubmit(e.target);
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Focus first input in modal
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');

            // Reset form if exists
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
                // Clear validation errors
                this.clearFormErrors(form);
            }
        }
    }

    closeAllModals() {
        const modals = document.querySelectorAll('[id$="-modal"]');
        modals.forEach(modal => {
            this.closeModal(modal.id);
        });
    }

    async handleFormSubmit(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn?.textContent;

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';
        }

        try {
            const formData = new FormData(form);
            const method = form.getAttribute('data-method') || 'POST';
            const action = form.getAttribute('action');

            // Add _method field for PUT/PATCH/DELETE
            if (method.toUpperCase() !== 'POST') {
                formData.append('_method', method);
            }

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                formData.append('_token', csrfToken);
            }

            const response = await fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw errorData;
            }

            const data = await response.json();

            // Show success message
            this.showNotification(data.message || 'Operasi berhasil!', 'success');

            // Close modal
            const modal = form.closest('[id$="-modal"]');
            if (modal) {
                this.closeModal(modal.id);
            }

            // Handle success callback
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else if (data.reload) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else if (data.callback) {
                // Execute custom callback
                eval(data.callback);
            }

        } catch (error) {
            console.error('Error:', error);

            if (error.errors) {
                // Show validation errors
                this.showFormErrors(form, error.errors);
                this.showNotification('Periksa kembali form Anda.', 'error');
            } else {
                // Show general error message
                this.showNotification(error.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        } finally {
            // Reset button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    }

    showFormErrors(form, errors) {
        // Clear previous errors
        this.clearFormErrors(form);

        // Show new errors
        Object.keys(errors).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                // Add error styling
                field.classList.add('border-red-500', 'focus:border-red-500');

                // Create error message element
                const errorElement = document.createElement('p');
                errorElement.className = 'mt-1 text-sm text-red-600 field-error';
                errorElement.textContent = Array.isArray(errors[fieldName]) ? errors[fieldName][0] : errors[fieldName];

                // Insert error message after field
                field.parentNode.appendChild(errorElement);
            }
        });
    }

    clearFormErrors(form) {
        // Remove error styling
        form.querySelectorAll('.border-red-500').forEach(field => {
            field.classList.remove('border-red-500', 'focus:border-red-500');
        });

        // Remove error messages
        form.querySelectorAll('.field-error').forEach(error => {
            error.remove();
        });
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.crud-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `crud-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;

        // Set color based on type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };

        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        // Add to DOM
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    // Universal modal operations for all CRUD
    async loadModalContent(modalId, url, options = {}) {
        const {
            title = null,
            size = null,
            loadingText = 'Memuat...',
            onSuccess = null,
            onError = null,
            method = 'GET',
            data = null
        } = options;

        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal with ID '${modalId}' not found`);
            return false;
        }

        const modalBody = modal.querySelector('.px-6.py-4');
        if (!modalBody) {
            console.error(`Modal body not found in modal '${modalId}'`);
            return false;
        }

        // Update modal title if provided
        if (title) {
            const titleElement = modal.querySelector('.modal-title');
            if (titleElement) {
                titleElement.textContent = title;
            }
        }

        // Update modal size if provided
        if (size) {
            const modalPanel = modal.querySelector('.bg-white');
            if (modalPanel) {
                // Remove existing size classes
                modalPanel.classList.remove('max-w-md', 'max-w-lg', 'max-w-xl', 'max-w-2xl', 'max-w-3xl', 'max-w-4xl', 'max-w-5xl', 'max-w-6xl', 'max-w-7xl');
                // Add new size class
                const sizeClasses = {
                    sm: 'max-w-md',
                    md: 'max-w-lg',
                    lg: 'max-w-xl',
                    xl: 'max-w-2xl',
                    '2xl': 'max-w-3xl',
                    '3xl': 'max-w-4xl',
                    '4xl': 'max-w-5xl',
                    '5xl': 'max-w-6xl',
                    '6xl': 'max-w-7xl',
                    full: 'max-w-full'
                };
                modalPanel.classList.add(sizeClasses[size] || 'max-w-lg');
            }
        }

        // Show loading state
        modalBody.innerHTML = `
            <div class="flex flex-col justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4"></div>
                <p class="text-gray-600">${loadingText}</p>
            </div>
        `;

        this.openModal(modalId);

        try {
            // Prepare fetch options
            const fetchOptions = {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': method === 'DELETE' ? 'application/json' : 'text/html'
                }
            };

            // Add body for POST/PUT/DELETE requests
            if (method !== 'GET' && data) {
                if (data instanceof FormData) {
                    fetchOptions.body = data;
                } else {
                    fetchOptions.headers['Content-Type'] = 'application/json';
                    fetchOptions.body = JSON.stringify(data);
                }
            }

            const response = await fetch(url, fetchOptions);

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }

            if (method === 'DELETE') {
                // For DELETE operations, expect JSON response
                const result = await response.json();
                if (onSuccess) onSuccess(result);

                // Show success notification
                this.showNotification(result.message || 'Operasi berhasil!', 'success');

                // Close modal and optionally reload
                setTimeout(() => {
                    this.closeModal(modalId);
                    if (result.reload) {
                        window.location.reload();
                    }
                }, 1000);

                return true;
            }

            // For GET/POST/PUT operations, expect HTML response
            const html = await response.text();
            modalBody.innerHTML = html;

            // Re-initialize any scripts in the loaded content
            const scripts = modalBody.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                newScript.textContent = script.textContent;
                modalBody.appendChild(newScript);
            });

            // Focus first input if form is loaded
            const firstInput = modalBody.querySelector('input, textarea, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }

            if (onSuccess) onSuccess({ html, modalId });

            return true;

        } catch (error) {
            console.error('Error loading modal content:', error);

            // Show error state
            modalBody.innerHTML = `
                <div class="text-center py-8">
                    <div class="text-red-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-red-600 mb-4">Gagal memuat konten: ${error.message}</p>
                    <button onclick="crudModal.closeModal('${modalId}')" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                        Tutup
                    </button>
                </div>
            `;

            if (onError) onError(error);
            return false;
        }
    }

    // Universal CRUD operations
    async createRecord(modalId, url, options = {}) {
        return await this.loadModalContent(modalId, url, {
            ...options,
            title: options.title || 'Tambah Data Baru',
            loadingText: options.loadingText || 'Memuat formulir...'
        });
    }

    async viewRecord(modalId, url, options = {}) {
        return await this.loadModalContent(modalId, url, {
            ...options,
            title: options.title || 'Detail Data',
            loadingText: options.loadingText || 'Memuat detail...'
        });
    }

    async editRecord(modalId, url, options = {}) {
        return await this.loadModalContent(modalId, url, {
            ...options,
            title: options.title || 'Edit Data',
            loadingText: options.loadingText || 'Memuat formulir edit...'
        });
    }

    async deleteRecord(url, options = {}) {
        const {
            confirmMessage = 'Apakah Anda yakin ingin menghapus data ini?',
            successMessage = 'Data berhasil dihapus!',
            errorMessage = 'Gagal menghapus data. Silakan coba lagi.',
            onSuccess = null,
            onError = null
        } = options;

        if (!confirm(confirmMessage)) {
            return false;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || errorMessage);
            }

            const result = await response.json();

            // Show success notification
            this.showNotification(result.message || successMessage, 'success');

            // Execute callback
            if (onSuccess) onSuccess(result);

            // Handle success actions
            if (result.reload) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else if (result.redirect) {
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            }

            return true;

        } catch (error) {
            console.error('Delete error:', error);
            this.showNotification(error.message || errorMessage, 'error');

            if (onError) onError(error);
            return false;
        }
    }

    // Batch operations
    async bulkDelete(url, selectedIds, options = {}) {
        const {
            confirmMessage = `Apakah Anda yakin ingin menghapus ${selectedIds.length} data yang dipilih?`,
            successMessage = 'Data yang dipilih berhasil dihapus!',
            errorMessage = 'Gagal menghapus data. Silakan coba lagi.'
        } = options;

        if (!confirm(confirmMessage)) {
            return false;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            selectedIds.forEach(id => formData.append('ids[]', id));
            formData.append('_token', csrfToken);

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || errorMessage);
            }

            const result = await response.json();
            this.showNotification(result.message || successMessage, 'success');

            if (result.reload) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }

            return true;

        } catch (error) {
            console.error('Bulk delete error:', error);
            this.showNotification(error.message || errorMessage, 'error');
            return false;
        }
    }
}

// Initialize CrudModal immediately
window.crudModal = new CrudModal();

// Global functions for easy access
function openCrudModal(modalId) {
    if (window.crudModal) {
        window.crudModal.openModal(modalId);
    } else {
        console.error('CrudModal not initialized yet');
        // Fallback: wait a bit and try again
        setTimeout(() => {
            if (window.crudModal) {
                window.crudModal.openModal(modalId);
            }
        }, 100);
    }
}

function closeCrudModal(modalId) {
    if (window.crudModal) {
        window.crudModal.closeModal(modalId);
    } else {
        console.error('CrudModal not initialized yet');
    }
}

function loadModalContent(modalId, url, options = {}) {
    if (window.crudModal) {
        return window.crudModal.loadModalContent(modalId, url, options);
    } else {
        console.error('CrudModal not initialized yet');
        // Fallback: wait a bit and try again
        setTimeout(() => {
            if (window.crudModal) {
                return window.crudModal.loadModalContent(modalId, url, options);
            } else {
                alert('Modal system is loading. Please try again in a moment.');
            }
        }, 100);
    }
}

// Universal CRUD functions for easy access
function createRecord(modalId, url, options = {}) {
    if (window.crudModal) {
        return window.crudModal.createRecord(modalId, url, options);
    } else {
        console.error('CrudModal not initialized yet');
        return false;
    }
}

function viewRecord(modalId, url, options = {}) {
    if (window.crudModal) {
        return window.crudModal.viewRecord(modalId, url, options);
    } else {
        console.error('CrudModal not initialized yet');
        return false;
    }
}

function editRecord(modalId, url, options = {}) {
    if (window.crudModal) {
        return window.crudModal.editRecord(modalId, url, options);
    } else {
        console.error('CrudModal not initialized yet');
        return false;
    }
}

function deleteRecord(url, options = {}) {
    if (window.crudModal) {
        return window.crudModal.deleteRecord(url, options);
    } else {
        console.error('CrudModal not initialized yet');
        return false;
    }
}

function bulkDelete(url, selectedIds, options = {}) {
    if (window.crudModal) {
        return window.crudModal.bulkDelete(url, selectedIds, options);
    } else {
        console.error('CrudModal not initialized yet');
        return false;
    }
}

// Also assign to window for global access
window.openCrudModal = openCrudModal;
window.closeCrudModal = closeCrudModal;
window.loadModalContent = loadModalContent;
window.createRecord = createRecord;
window.viewRecord = viewRecord;
window.editRecord = editRecord;
window.deleteRecord = deleteRecord;
window.bulkDelete = bulkDelete;
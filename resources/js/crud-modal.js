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

    // Load content into modal via AJAX
    async loadModalContent(modalId, url) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const modalBody = modal.querySelector('.px-6.py-4');
        if (modalBody) {
            // Show loading state
            modalBody.innerHTML = `
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
            `;

            this.openModal(modalId);

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const html = await response.text();
                modalBody.innerHTML = html;

                // Re-initialize any scripts in the loaded content
                const scripts = modalBody.querySelectorAll('script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    modalBody.appendChild(newScript);
                });

            } catch (error) {
                console.error('Error loading modal content:', error);
                modalBody.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <p>Gagal memuat konten. Silakan coba lagi.</p>
                        <button onclick="crudModal.closeModal('${modalId}')" class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                            Tutup
                        </button>
                    </div>
                `;
            }
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

function loadModalContent(modalId, url) {
    if (window.crudModal) {
        window.crudModal.loadModalContent(modalId, url);
    } else {
        console.error('CrudModal not initialized yet');
        // Fallback: wait a bit and try again
        setTimeout(() => {
            if (window.crudModal) {
                window.crudModal.loadModalContent(modalId, url);
            } else {
                alert('Modal system is loading. Please try again in a moment.');
            }
        }, 100);
    }
}

// Also assign to window for global access
window.openCrudModal = openCrudModal;
window.closeCrudModal = closeCrudModal;
window.loadModalContent = loadModalContent;
/**
 * Toast Notification System
 */

const ToastManager = {
    container: null,
    template: null,
    actionTemplate: null,
    duration: 10000, // 10 detik default
    
    /**
     * Inisialisasi toast manager
     */
    init() {
        this.container = document.getElementById('toast-container');
        this.template = document.getElementById('toast-template');
        this.actionTemplate = document.getElementById('toast-action-template');
        
        if (!this.container || !this.template) {
            console.error('Toast container or template not found');
            return;
        }
    },
    
    /**
     * Tampilkan toast notification
     * 
     * @param {string} type - Tipe toast: 'success', 'error', 'warning', 'info'
     * @param {string} message - Pesan yang akan ditampilkan
     * @param {number} duration - Durasi tampil dalam ms
     */
    show(type, message, duration = null) {
        if (!this.container || !this.template) {
            this.init();
        }
        
        duration = duration || this.duration;
        
        // Clone template
        const toast = this.template.content.cloneNode(true);
        const toastElement = toast.querySelector('.toast-item');
        const iconContainer = toast.querySelector('.toast-icon');
        const iconSvg = toast.querySelector('.toast-icon svg');
        const messageElement = toast.querySelector('.toast-message');
        const closeButton = toast.querySelector('.toast-close');
        
        // Set message
        messageElement.textContent = message;
        
        // Set icon dan warna berdasarkan tipe
        const config = this.getConfig(type);
        iconContainer.className = `toast-icon inline-flex items-center justify-center shrink-0 w-8 h-8 rounded ${config.iconBg}`;
        iconSvg.innerHTML = config.iconPath;
        iconSvg.classList.add(config.iconColor);
        
        // Event listener untuk tombol close
        closeButton.addEventListener('click', () => {
            this.dismiss(toastElement);
        });
        
        // Tambahkan ke container
        this.container.appendChild(toast);
        
        // Auto dismiss setelah duration
        setTimeout(() => {
            this.dismiss(toastElement);
        }, duration);
    },
    
    /**
     * Tutup/dismiss toast
     * 
     * @param {HTMLElement} toastElement - Element toast yang akan ditutup
     */
    dismiss(toastElement) {
        if (!toastElement) return;
        
        // Tambahkan animasi slide-out
        toastElement.classList.remove('animate-slide-in');
        toastElement.classList.add('animate-slide-out');
        
        // Hapus element setelah animasi selesai
        setTimeout(() => {
            if (toastElement.parentNode) {
                toastElement.parentNode.removeChild(toastElement);
            }
        }, 300);
    },
    
    /**
     * Tutup semua toast yang ada
     */
    dismissAll() {
        const toasts = this.container.querySelectorAll('.toast-item');
        toasts.forEach(toast => this.dismiss(toast));
    },
    
    /**
     * Konfigurasi untuk setiap tipe toast
     * 
     * @param {string} type - Tipe toast
     * @returns {Object} Konfigurasi icon dan warna
     */
    getConfig(type) {
        const configs = {
            success: {
                iconBg: 'text-fg-success bg-success-soft',
                iconColor: 'text-fg-success',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"/>'
            },
            error: {
                iconBg: 'text-fg-danger bg-danger-soft',
                iconColor: 'text-fg-danger',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>'
            },
            warning: {
                iconBg: 'text-fg-warning bg-warning-soft',
                iconColor: 'text-fg-warning',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'
            },
            info: {
                iconBg: 'text-brand bg-brand-soft',
                iconColor: 'text-brand',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>'
            }
        };
        
        return configs[type] || configs.info;
    },
    
    /**
     * Konfigurasi untuk toast action (konfirmasi CRUD)
     * 
     * @param {string} actionType - Tipe aksi: 'delete', 'update', 'confirm'
     * @returns {Object} Konfigurasi untuk action toast
     */
    getActionConfig(actionType) {
        const configs = {
            delete: {
                title: 'Konfirmasi Hapus',
                iconBg: 'bg-danger-soft',
                iconColor: 'text-fg-danger',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>',
                cancelBtn: {
                    text: 'Batal',
                    class: 'text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-neutral-tertiary'
                },
                confirmBtn: {
                    text: 'Hapus',
                    class: 'text-white bg-danger border-transparent hover:bg-danger-strong focus:ring-danger-medium'
                }
            },
            update: {
                title: 'Konfirmasi Update',
                iconBg: 'bg-warning-soft',
                iconColor: 'text-fg-warning',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                cancelBtn: {
                    text: 'Batal',
                    class: 'text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-neutral-tertiary'

                },
                confirmBtn: {
                    text: 'Update',
                    class: 'text-white bg-warning border-transparent hover:bg-warning-strong focus:ring-warning-medium'
                }
            },
            confirm: {
                title: 'Konfirmasi',
                iconBg: 'bg-brand-soft',
                iconColor: 'text-brand',
                iconPath: '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                cancelBtn: {
                    text: 'Batal',
                    class: 'text-body bg-neutral-secondary-medium border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-neutral-tertiary'
                },
                confirmBtn: {
                    text: 'Ya',
                    class: 'text-white bg-brand border-transparent hover:bg-brand-strong focus:ring-brand-medium'
                }
            }
        };
        
        return configs[actionType] || configs.confirm;
    },
    
    /**
     * Tampilkan toast dengan action buttons untuk konfirmasi
     * 
     * @param {Object} options - Konfigurasi toast action
     * @param {string} options.type - Tipe action: 'delete', 'update', 'confirm'
     * @param {string} options.title - Judul toast (opsional, akan override default)
     * @param {string} options.message - Pesan konfirmasi
     * @param {Function} options.onConfirm - Callback ketika user klik konfirmasi
     * @param {Function} options.onCancel - Callback ketika user klik batal (opsional)
     * @param {string} options.confirmText - Text tombol konfirmasi (opsional)
     * @param {string} options.cancelText - Text tombol batal (opsional)
     */
    showAction(options) {
        if (!this.container || !this.actionTemplate) {
            this.init();
        }
        
        const {
            type = 'confirm',
            title,
            message,
            onConfirm,
            onCancel,
            confirmText,
            cancelText
        } = options;
        
        // Clone template
        const toast = this.actionTemplate.content.cloneNode(true);
        const toastElement = toast.querySelector('.toast-action-item');
        const iconContainer = toast.querySelector('.toast-action-icon');
        const iconSvg = toast.querySelector('.toast-action-icon svg');
        const titleElement = toast.querySelector('.toast-action-title');
        const messageElement = toast.querySelector('.toast-action-message');
        const cancelButton = toast.querySelector('.toast-action-cancel');
        const confirmButton = toast.querySelector('.toast-action-confirm');
        const closeButton = toast.querySelector('.toast-action-close');
        
        // Get configuration
        const config = this.getActionConfig(type);
        
        // Set content
        titleElement.textContent = title || config.title;
        messageElement.textContent = message;
        
        // Set icon
        iconContainer.className = `toast-action-icon inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full ${config.iconBg}`;
        iconSvg.innerHTML = config.iconPath;
        iconSvg.classList.add(config.iconColor);
        

        
        // Set button text & styles
        cancelButton.textContent = cancelText || config.cancelBtn.text;
        cancelButton.className = `toast-action-cancel flex-1 text-sm font-medium px-3 py-2 rounded-base border transition-colors focus:outline-none focus:ring-2 ${config.cancelBtn.class}`;
        
        confirmButton.textContent = confirmText || config.confirmBtn.text;
        confirmButton.className = `toast-action-confirm flex-1 text-sm font-medium px-3 py-2 rounded-base border transition-colors focus:outline-none focus:ring-2 ${config.confirmBtn.class}`;
        
        // Event listeners
        const dismissToast = () => {
            this.dismiss(toastElement);
        };
        
        cancelButton.addEventListener('click', () => {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
            dismissToast();
        });
        
        confirmButton.addEventListener('click', () => {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
            dismissToast();
        });
        
        closeButton.addEventListener('click', dismissToast);
        
        // Tambahkan ke container
        this.container.appendChild(toast);
        
        // Action toast tidak auto-dismiss, harus user yang pilih
    },
    
    // Shorthand methods
    success(message, duration) {
        this.show('success', message, duration);
    },
    
    error(message, duration) {
        this.show('error', message, duration);
    },
    
    warning(message, duration) {
        this.show('warning', message, duration);
    },
    
    info(message, duration) {
        this.show('info', message, duration);
    },
    
    // Shorthand methods untuk action toast
    confirmDelete(message, onConfirm, options = {}) {
        this.showAction({
            type: 'delete',
            message,
            onConfirm,
            ...options
        });
    },
    
    confirmUpdate(message, onConfirm, options = {}) {
        this.showAction({
            type: 'update',
            message,
            onConfirm,
            ...options
        });
    },
    
    confirm(message, onConfirm, options = {}) {
        this.showAction({
            type: 'confirm',
            message,
            onConfirm,
            ...options
        });
    }
};

// Inisialisasi ketika DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        ToastManager.init();
    });
} else {
    ToastManager.init();
}

// Export ke global scope
window.ToastManager = ToastManager;

// Alias untuk kemudahan penggunaan
window.showToast = (type, message, duration) => ToastManager.show(type, message, duration);

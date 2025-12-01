/**
 * Toast Notification System
 */

const ToastManager = {
    container: null,
    template: null,
    duration: 10000, // 10 detik default
    
    /**
     * Inisialisasi toast manager
     */
    init() {
        this.container = document.getElementById('toast-container');
        this.template = document.getElementById('toast-template');
        
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

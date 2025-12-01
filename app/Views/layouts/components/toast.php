<!-- Toast Container - akan muncul di pojok kanan bawah -->
<div id="toast-container" class="fixed end-5 bottom-5 z-50 space-y-3">
    <!-- Toast akan di-inject di sini oleh JavaScript -->
</div>

<!-- Template Toast (hidden, akan di-clone oleh JS) -->
<template id="toast-template">
    <div class="toast-item flex items-center w-full max-w-xs p-4 text-body bg-neutral-primary-soft rounded-base shadow-md border border-default animate-slide-in" role="alert">
        <!-- Icon Container -->
        <div class="toast-icon inline-flex items-center justify-center shrink-0 w-8 h-8 rounded">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <!-- Icon path akan diupdate oleh JS -->
            </svg>
        </div>
        
        <!-- Message -->
        <div class="toast-message ms-3 text-sm font-normal flex-1"></div>
        
        <!-- Close Button -->
        <button type="button" class="toast-close ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent border-0 hover:bg-neutral-secondary-medium rounded p-1.5 focus:ring-2 focus:ring-neutral-tertiary transition-colors" aria-label="Close">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
            </svg>
        </button>
    </div>
</template>

<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-slide-out {
    animation: slide-out 0.3s ease-in;
}
</style>

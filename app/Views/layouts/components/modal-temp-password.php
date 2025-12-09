<!-- Modal Template untuk Temporary Password -->
<div id="temp-password-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-neutral-primary/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-lg p-4 md:p-6">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-warning-soft text-warning-strong">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-heading">
                        Akun Karyawan Berhasil Diaktifkan
                    </h3>
                </div>
                <button type="button" id="close-temp-password-modal" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center transition-colors" aria-label="Close modal">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="space-y-4 md:space-y-6 py-4 md:py-6">
                <!-- Warning Alert -->
                <div class="flex items-start p-4 text-warning-strong rounded-base bg-warning-soft border border-warning-subtle" role="alert">
                    <svg class="shrink-0 w-5 h-5 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div class="ms-3 text-sm">
                        <span class="font-semibold">Perhatian!</span> Password temporary ini hanya ditampilkan sekali. Segera kirimkan ke karyawan terkait sebelum menutup modal ini.
                    </div>
                </div>

                <!-- Credential Information -->
                <div class="space-y-3">
                    <p class="text-body text-sm">
                        Informasi login berikut harus segera disampaikan kepada karyawan:
                    </p>

                    <!-- Email Field -->
                    <label for="temp-email" class="block mb-2.5 text-sm font-medium text-heading">Email</label>
                    <div class="relative">
                        <input id="temp-email" type="text" class="col-span-6 bg-neutral-secondary-medium border border-default-medium text-body text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" value="" disabled readonly>
                        <button data-copy-to-clipboard-target="temp-email" onclick="copyToClipboard('temp-email')" class="absolute flex items-center end-1.5 top-1/2 -translate-y-1/2 text-body bg-neutral-primary-strong border border-default-strong hover:bg-neutral-secondary-strong/70 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft font-medium leading-5 rounded text-xs px-3 py-1.5 focus:outline-none">
                            <span id="default-message">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z" />
                                    </svg>
                                    <span class="text-xs font-semibold">Copy</span>
                                </span>
                            </span>
                            <span id="success-message" class="hidden">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-fg-brand me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                                    </svg>
                                    <span class="text-xs font-semibold text-fg-brand">Copied</span>
                                </span>
                            </span>
                        </button>
                    </div>

                    <!-- Temporary Password Field -->
                    <label for="temp-password" class="block mb-2.5 text-sm font-medium text-heading">Temp password</label>
                    <div class="relative">
                        <input id="temp-password" type="text" class="col-span-6 bg-neutral-secondary-medium border border-default-medium text-body text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" value="" disabled readonly>
                        <button data-copy-to-clipboard-target="temp-password" onclick="copyToClipboard('temp-password')" class="absolute flex items-center end-1.5 top-1/2 -translate-y-1/2 text-body bg-neutral-primary-strong border border-default-strong hover:bg-neutral-secondary-strong/70 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft font-medium leading-5 rounded text-xs px-3 py-1.5 focus:outline-none">
                            <span id="default-message">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z" />
                                    </svg>
                                    <span class="text-xs font-semibold">Copy</span>
                                </span>
                            </span>
                            <span id="success-message" class="hidden">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 text-fg-brand me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                                    </svg>
                                    <span class="text-xs font-semibold text-fg-brand">Copied</span>
                                </span>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-neutral-secondary-soft border border-default rounded-base p-4">
                    <h4 class="text-sm font-semibold text-heading mb-2">Instruksi untuk Karyawan:</h4>
                    <ol class="list-decimal list-inside space-y-1 text-sm text-body">
                        <li>Gunakan email dan password di atas untuk login</li>
                        <li>Sistem akan meminta untuk mengganti password setelah login pertama kali</li>
                        <li>Pastikan menggunakan password yang kuat dan mudah diingat</li>
                        <li>Jangan bagikan password kepada siapapun</li>
                    </ol>
                </div>

                <!-- Critical Warning -->
                <div class="flex items-start p-4 text-danger-strong rounded-base bg-danger-soft border border-danger-subtle" role="alert">
                    <svg class="shrink-0 w-5 h-5 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <div class="ms-3 text-sm">
                        <span class="font-semibold">Penting!</span> Setelah modal ini ditutup, password temporary <span class="font-semibold">tidak dapat dilihat kembali</span>. Pastikan sudah menyalin informasi di atas.
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center justify-between border-t border-default space-x-4 pt-4 md:pt-5">
                <button id="copy-all-button" onclick="copyAllCredentials()" class="inline-flex items-center text-body bg-neutral-primary border border-default hover:bg-neutral-secondary-soft hover:text-heading focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    <span id="default-message">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z" />
                            </svg>
                            <span class="text-xs font-semibold">Salin Semua</span>
                        </span>
                    </span>
                    <span id="success-message" class="hidden">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 text-fg-brand me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                            </svg>
                            <span class="text-xs font-semibold text-fg-brand">Berhasil Disalin</span>
                        </span>
                    </span>
                </button>
                <button type="button" id="confirm-close-modal" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none transition-colors">
                    Saya Sudah Menyalin Password
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Copy to clipboard function
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const defaultIcon = element.nextElementSibling.querySelector('#default-message');
        const successIcon = element.nextElementSibling.querySelector('#success-message');
        element.select();
        element.setSelectionRange(0, 99999); // For mobile devices

        navigator.clipboard.writeText(element.value).then(() => {
            ToastManager.success('Berhasil disalin ke clipboard!', 3000);
            // Show success icon
            defaultIcon.classList.add('hidden');
            successIcon.classList.remove('hidden');
        }).catch(err => {
            ToastManager.error('Gagal menyalin ke clipboard', 3000);
        });
    }

    // Copy all credentials
    function copyAllCredentials() {
        const btn = document.getElementById('copy-all-button');
        const email = document.getElementById('temp-email').value;
        const password = document.getElementById('temp-password').value;
        const text = `Email: ${email}\nPassword: ${password}`;

        navigator.clipboard.writeText(text).then(() => {
            ToastManager.success('Semua kredensial berhasil disalin!', 3000);
            btn.querySelector('#default-message').classList.add('hidden');
            btn.querySelector('#success-message').classList.remove('hidden');
        }).catch(err => {
            ToastManager.error('Gagal menyalin kredensial', 3000);
        });
    }
</script>
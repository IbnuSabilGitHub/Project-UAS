<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-neutral-secondary-soft px-4">
    <div class="max-w-md w-full bg-neutral-primary-soft shadow-md rounded-base p-8 text-center">
        <div class="mb-6">
            <svg class="mx-auto h-24 w-24 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-heading mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-heading mb-4">Akses Ditolak</h2>
        <p class="text-body mb-8">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="javascript:history.back()"
                class="inline-block bg-neutral-secondary-medium hover:bg-neutral-tertiary-soft text-heading font-medium px-6 py-3 rounded-base border border-default transition duration-200">
                Kembali
            </a>
            <a href="<?= url('/') ?>"
                class="inline-block bg-brand hover:bg-brand-strong text-white font-medium px-6 py-3 rounded-base shadow-xs transition duration-200">
                Ke Halaman Utama
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

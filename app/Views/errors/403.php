<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen bg-base-200 flex items-center justify-center p-4">
    <div class="max-w-5xl w-full flex flex-row items-center justify-center gap-16">
        <div class="shrink-0">
            <img
                src="<?= asset('img/403.png') ?>"
                alt="403 illustration"
                class="h-96 object-cover" />
        </div>

        <div class="text-left">
            <h1 class="text-9xl font-bold text-heading mb-4">403</h1>
            <h2 class="text-3xl font-semibold text-heading mb-3">
                Akses Ditolak
            </h2>
            <p class="text-body text-lg mb-8 max-w-md">
                Anda tidak memiliki izin untuk mengakses halaman ini.
            </p>
            <div class="space-x-6">
                <a
                    href="<?= url('/') ?>"
                    class="inline-flex items-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-nonel">
                    <i class="fa-solid fa-house me-1.5 -ms-0.5"></i>
                    Go to Homepage
                </a>
                <a href="javascript:history.back()"
                    class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    <i class="fa-solid fa-angle-left me-1.5 -ms-0.5"></i>
                    Kembali
                </a>
            </div>


        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
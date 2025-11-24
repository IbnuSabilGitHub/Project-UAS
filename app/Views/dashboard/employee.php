<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (!empty($success)): ?>
        <div class="flex items-center p-4 mb-6 text-green-800 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>
    
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-8 border border-default max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-heading mb-4">Dashboard Karyawan</h1>
        <p class="mb-8 text-body">Halo, <strong class="text-heading"><?= htmlspecialchars($username) ?></strong>. Fitur karyawan akan segera tersedia.</p>
        
        <div class="bg-neutral-secondary-medium border border-default-medium rounded-base p-6 mb-6">
            <h2 class="text-lg font-semibold text-heading mb-4">Fitur yang Akan Datang</h2>
            <ul class="space-y-3">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-medium mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-body">Check-in / Check-out <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">Coming Soon</span></span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-medium mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-body">Pengajuan Cuti <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">Coming Soon</span></span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-medium mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-body">Riwayat Cuti <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full ml-2">Coming Soon</span></span>
                </li>
            </ul>
        </div>
        
        <div class="border-t border-default pt-6">
            <a href="<?= url('/logout') ?>" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-5 py-2.5 rounded-base shadow-xs transition duration-200">
                Logout
            </a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
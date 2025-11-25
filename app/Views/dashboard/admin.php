<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="container mx-auto px-4 py-8">
    <?php if (!empty($success)): ?>
        <div class="flex items-center p-4 mb-6 text-green-600 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>
    
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-8 border border-default">
        <h1 class="text-3xl font-bold text-heading mb-4">Admin Dashboard</h1>
        <p class="mb-8 text-body">Selamat datang, <strong class="text-heading"><?= htmlspecialchars($username) ?></strong> 
            <span class="inline-block bg-brand-soft text-brand px-3 py-1 rounded-full text-sm font-medium ml-2"><?= htmlspecialchars($role) ?></span>
        </p>
        
        <div class="grid md:grid-cols-3 gap-6">
            <a href="<?= url('/admin/karyawan') ?>" 
               class="block bg-neutral-secondary-medium shadow-xs hover:shadow-md transition-all duration-200 rounded-base p-6 border border-default hover:border-brand">
                <h2 class="text-xl font-semibold text-heading mb-2">Manajemen Karyawan</h2>
                <p class="text-sm text-body">Lihat, tambah, nonaktifkan, hapus permanen karyawan.</p>
            </a>

            <a href="<?= url('/admin/cuti') ?>" 
               class="block bg-neutral-secondary-medium shadow-xs hover:shadow-md transition-all duration-200 rounded-base p-6 border border-default hover:border-brand">
                <h2 class="text-xl font-semibold text-heading mb-2">Pengajuan Cuti</h2>
                <p class="text-sm text-body">Kelola pengajuan cuti karyawan dan approve/reject.</p>
            </a>
            
            <a href="<?= url('/admin/attendance') ?>" 
               class="block bg-neutral-secondary-medium shadow-xs hover:shadow-md transition-all duration-200 rounded-base p-6 border border-default hover:border-brand">
                <h2 class="text-xl font-semibold text-heading mb-2">Absensi</h2>
                <p class="text-sm text-body">Lihat, filter, dan export data absensi karyawan.</p>
            </a>

        </div>
        
        <div class="mt-8 border-t border-default pt-6">
            <a href="<?= url('/logout') ?>" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-5 py-2.5 rounded-base shadow-xs transition duration-200">
                Logout
            </a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
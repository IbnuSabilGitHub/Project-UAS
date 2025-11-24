<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-6xl mx-auto py-8 px-4">
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Dashboard Karyawan</h1>
        <p class="text-gray-700">Selamat datang, <strong><?= htmlspecialchars($username) ?></strong></p>
    </div>

    <!-- Menu Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <!-- Check-in / Check-out -->
        <a href="<?= url('/karyawan/attendance') ?>" class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300 border-t-4 border-blue-500">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-semibold mb-2">Absensi</h2>
            <p class="text-gray-600">Check-in dan Check-out harian</p>
            <div class="mt-4 text-blue-600 font-medium">
                Buka →
            </div>
        </a>

        <!-- Pengajuan Cuti -->
        <a href="<?= url('/karyawan/leave/create') ?>" class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300 border-t-4 border-green-500">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-semibold mb-2">Pengajuan Cuti</h2>
            <p class="text-gray-600">Ajukan permohonan cuti baru</p>
            <div class="mt-4 text-green-600 font-medium">
                Ajukan →
            </div>
        </a>

        <!-- Riwayat Cuti -->
        <a href="<?= url('/karyawan/leave') ?>" class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition duration-300 border-t-4 border-purple-500">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-semibold mb-2">Riwayat Cuti</h2>
            <p class="text-gray-600">Lihat status pengajuan cuti</p>
            <div class="mt-4 text-purple-600 font-medium">
                Lihat →
            </div>
        </a>
    </div>

    <!-- Logout Button -->
    <div class="text-center">
        <a href="<?= url('/logout') ?>" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg transition duration-200">
            Logout
        </a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
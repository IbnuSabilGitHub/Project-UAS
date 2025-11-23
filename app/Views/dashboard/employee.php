<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-3xl mx-auto py-8 px-4">
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <h1 class="text-3xl font-bold mb-4">Dashboard Karyawan</h1>
    <p class="mb-6 text-gray-700">Halo, <strong><?= htmlspecialchars($username) ?></strong>. Fitur karyawan akan segera tersedia.</p>
    <ul class="list-disc ml-6 text-gray-600 space-y-2">
        <li>Check-in / Check-out (Belum jadi bang ☺️)</li>
        <li>Pengajuan Cuti (Belum jadi bang ☺️)</li>
        <li>Riwayat Cuti (Belum jadi bang ☺️)</li>
    </ul>
    <div class="mt-10">
        <a href="<?= url('/logout') ?>" class="inline-block bg-red-600 text-white px-5 py-2 rounded">Logout</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
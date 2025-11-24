<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-4xl mx-auto py-8 px-4">
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>
    <p class="mb-6 text-gray-700">Selamat datang, <strong><?= htmlspecialchars($username) ?></strong> (role: <?= htmlspecialchars($role) ?>)</p>
    <div class="grid md:grid-cols-3 gap-6">
        <a href="/admin/karyawan" class="block bg-white shadow hover:shadow-md transition rounded p-6 border">
            <h2 class="text-xl font-semibold mb-2">Manajemen Karyawan</h2>
            <p class="text-sm text-gray-600">Lihat, tambah, nonaktifkan, hapus permanen karyawan.</p>
        </a>
        <a href="#" class="block bg-white opacity-60 cursor-not-allowed rounded p-6 border">
            <h2 class="text-xl font-semibold mb-2">Absensi (Belum jadi bang ☺️)</h2>
            <p class="text-sm text-gray-600">Modul absensi akan ditambahkan.</p>
        </a>
        <a href="#" class="block bg-white opacity-60 cursor-not-allowed rounded p-6 border">
            <h2 class="text-xl font-semibold mb-2">Pengajuan Cuti (Belum jadi bang ☺️)</h2>
            <p class="text-sm text-gray-600">Modul cuti akan ditambahkan.</p>
        </a>
    </div>
    <div class="mt-10">
        <a href="/logout" class="inline-block bg-red-600 text-white px-5 py-2 rounded">Logout</a>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Ganti Password</h1>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= url('/change-password') ?>">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password Baru</label>
            <input type="password" name="new_password" class="w-full border px-3 py-2 rounded" required minlength="8">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" class="w-full border px-3 py-2 rounded" required minlength="8">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Simpan</button>
    </form>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard</h1>
        
        <div class="mb-6">
            <p class="text-lg mb-2">
                Selamat datang, <span class="font-semibold text-blue-600"><?= htmlspecialchars($username) ?></span>!
            </p>
            <p class="text-gray-600">
                Role: <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    <?= htmlspecialchars($role) ?>
                </span>
            </p>
        </div>
        <div>
            <?php if ($role === 'admin'): ?>
                <img src="https://i.pinimg.com/736x/75/ed/85/75ed8589172e9ca4518e1a8cff57a7f4.jpg" alt="aku admint">
            <?php else: ?>
                <img src="https://i.pinimg.com/736x/5e/46/d8/5e46d822293a57440a21cc3efd1f4b70.jpg" alt="aku user">
            <?php endif; ?>
        </div>

        <div class="border-t pt-6">
            <a href="<?= url('/logout') ?>" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2 rounded transition duration-200">
                Logout
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

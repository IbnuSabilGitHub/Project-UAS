<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8 flex items-center justify-center min-h-screen w-full">
    <?php if (isset($success)): ?>
        <div class="flex items-center p-4 mb-6 text-green-800 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>

    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-8 border border-default">
        <h1 class="text-3xl font-bold mb-6 text-heading">Dashboard</h1>
        
        <div class="mb-6">
            <p class="text-lg mb-2 text-body">
                Selamat datang, <span class="font-semibold text-brand"><?= htmlspecialchars($username) ?></span>!
            </p>
            <p class="text-body">
                Role: <span class="inline-block bg-brand-soft text-brand px-3 py-1 rounded-full text-sm font-medium">
                    <?= htmlspecialchars($role) ?>
                </span>
            </p>
        </div>
        
        <div class="border-t border-default pt-6">
            <a href="<?= url('/logout') ?>" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2.5 rounded-base shadow-xs transition duration-200">
                Logout
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

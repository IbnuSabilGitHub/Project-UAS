<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-md mx-auto mt-20 p-8 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Login HRIS</h1>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <!-- Gunakan htmlspecialchars (adalah fungsi bawaan PHP) untuk mencegah XSS,
              dengan ENT_QUOTES (mengonversi baik kutip tunggal maupun ganda) dan UTF-8 sebagai encoding -->
            <?= htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <!-- Cegah XSS -->
            <?= htmlspecialchars($success ?? '', ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('/login') ?>">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" 
                   id="username"
                   name="username" 
                   placeholder="Masukkan username"
                   class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" 
                   id="password"
                   name="password" 
                   placeholder="Masukkan password"
                   class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-3 rounded w-full transition duration-200">
            Login
        </button>
    </form>

    <!-- Bagian ini hanya untuk keperluan testing development saja, bisa dihapus nanti -->
    <div class="mt-6 p-4 bg-gray-50 rounded text-sm">
        <p class="font-semibold mb-2">Akun Testing:</p>
        <p>Admin: username=<code class="bg-gray-200 px-1">admin</code>, password=<code class="bg-gray-200 px-1">admin_password</code></p>
        <p>User: username=<code class="bg-gray-200 px-1">user1</code>, password=<code class="bg-gray-200 px-1">user1_password</code></p>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

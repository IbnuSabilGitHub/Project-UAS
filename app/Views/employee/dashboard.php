
<div class="p-4 sm:ml-64 mt-14">
    <?php if (!empty($success)): ?>
        <div class="flex items-center p-4 mb-6 text-green-600 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div class="ms-3 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
        </div>
    <?php endif; ?>

    <div class="text-body">
        <p class="font-bold text-lg mb-2">Statistik Cuti:</p>
        <pre><?= json_encode($statsLeave, JSON_PRETTY_PRINT) ?></pre>

        <p class="font-bold text-lg mb-2 mt-6">Statistik Absensi:</p>
        <pre><?= json_encode($statsAttendance, JSON_PRETTY_PRINT) ?></pre>
    </div>
```
</div>

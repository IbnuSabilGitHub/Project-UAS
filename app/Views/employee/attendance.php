<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="p-4 sm:ml-64 mt-14">
    <h1 class="text-3xl font-bold mb-6 text-heading">Absensi Saya</h1>

    <?php if (!empty($success)): ?>
        <div class="flex items-center p-4 mb-4 text-green-800 rounded-base bg-green-50" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($success) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="flex items-center p-4 mb-4 text-red-800 rounded-base bg-red-50" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Statistik Bulan Ini -->
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
        <h2 class="text-xl font-semibold mb-4 text-heading">Statistik Bulan Ini</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-neutral-primary-soft p-4 rounded-base border border-default">
                <p class="text-3xl font-bold text-heading"><?= $stats['total_days'] ?? 0 ?></p>
                <p class="text-sm text-body">Total Hari Hadir</p>
            </div>
            <div class="bg-neutral-primary-soft p-4 rounded-base border border-default">
                <p class="text-3xl font-bold text-heading"><?= $stats['on_time'] ?? 0 ?></p>
                <p class="text-sm text-body">Tepat Waktu</p>
            </div>
            <div class="bg-neutral-primary-soft p-4 rounded-base border border-default">
                <p class="text-3xl font-bold text-heading"><?= $stats['late'] ?? 0 ?></p>
                <p class="text-sm text-body">Terlambat</p>
            </div>
            <div class="bg-neutral-primary-soft p-4 rounded-base border border-default">
                <p class="text-3xl font-bold text-heading">
                    <?= isset($stats['avg_hours']) && $stats['avg_hours'] ? number_format($stats['avg_hours'], 1) : '0' ?>
                </p>
                <p class="text-sm text-body">Rata-rata Jam Kerja</p>
            </div>
        </div>
    </div>

    <!-- Status Hari Ini & Form Check-in/out -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Status Hari Ini -->
        <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
            <h2 class="text-xl font-semibold mb-4 text-heading">Status Hari Ini</h2>
            <div class="mb-4">
                <p class="text-body"><strong>Tanggal:</strong> <?= date('d F Y') ?></p>
                <p class="text-body"><strong>Waktu Sekarang:</strong> <span id="currentTime"><?= date('H:i:s') ?></span></p>
            </div>

            <?php if ($todayStatus): ?>
                <div class="bg-neutral-secondary-medium border-l-4 border-blue-500 p-4 mb-4">
                    <p class="font-semibold text-blue-700">Check-in: <?= date('H:i:s', strtotime($todayStatus['check_in'])) ?></p>
                    <?php if ($todayStatus['check_out']): ?>
                        <p class="font-semibold text-green-700">Check-out: <?= date('H:i:s', strtotime($todayStatus['check_out'])) ?></p>
                    <?php else: ?>
                        <p class="text-gray-600 text-sm mt-2">Belum check-out</p>
                    <?php endif; ?>

                    <?php if ($todayStatus['status'] === 'late'): ?>
                        <span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">Terlambat</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="flex items-start sm:items-center p-4 mb-4 text-sm text-fg-warning rounded-base bg-warning-soft border border-warning-subtle" role="alert">
                    <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p><span class="font-medium me-1">Warning alert!</span>Anda belum check-in hari ini</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form Check-in/out -->
        <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
            <h2 class="text-xl font-semibold mb-4 text-heading">Absensi</h2>

            <?php if (!$todayStatus): ?>
                <!-- Form Check-in -->
                <form action="<?= url('/karyawan/attendance/checkin') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-heading mb-2">Catatan (opsional)</label>
                        <textarea name="notes" rows="3"
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body"
                            placeholder="Masukkan catatan jika ada"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full text-white text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                        Check-in Sekarang
                    </button>
                </form>
            <?php elseif (!$todayStatus['check_out']): ?>
                <!-- Form Check-out -->
                <form action="<?= url('/karyawan/attendance/checkout') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-heading mb-2">Catatan (opsional)</label>
                        <textarea name="notes" rows="3"
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body"
                            placeholder="Masukkan catatan jika ada"></textarea>
                    </div>

                    <button type="submit" class="w-full text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Check-out Sekarang</button>
                </form>
            <?php else: ?>
                <div class="bg-neutral-secondary-medium p-4 rounded-base">
                    <p class="text-green-600 font-medium">Anda sudah check-in dan check-out hari ini</p>
                    <?php if ($todayStatus['status'] === 'present'): ?>
                        <p class="text-sm text-body mt-2">Terima kasih sudah absen tepat waktu!</p>
                    <?php elseif ($todayStatus['status'] === 'late'): ?>
                        <p class="text-sm text-body mt-2">Anda terlambat hari ini. Mohon perhatikan waktu absensi selanjutnya.</p>
                    <?php else: ?>
                        <p class="text-sm text-body mt-2">Anda melakukan half day hari ini.</p>
                    <?php endif; ?>

                </div>
            <?php endif; ?>
        </div>
    </div>



    <!-- Riwayat Absensi -->
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
        <h2 class="text-xl font-semibold mb-4 text-heading">Riwayat Absensi (30 Hari Terakhir)</h2>

        <?php if (empty($history)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-gray-600">Belum ada riwayat absensi</p>
            </div>
        <?php else: ?>
            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-neutral-secondary-medium border-b rounded-base border-default">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-6 py-3 font-medium">Check-in</th>
                            <th scope="col" class="px-6 py-3 font-medium">Check-out</th>
                            <th scope="col" class="px-6 py-3 font-medium">Durasi</th>
                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                            <th scope="col" class="px-6 py-3 font-medium">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $record): ?>
                            <tr class="bg-neutral-primary border-b border-default">
                                <td class="px-6 py-4">
                                    <?= date('d/m/Y', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= date('H:i:s', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($record['check_out']): ?>
                                        <?= date('H:i:s', strtotime($record['check_out'])) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($record['check_out']): ?>
                                        <?php
                                        $diff = strtotime($record['check_out']) - strtotime($record['check_in']);
                                        $hours = floor($diff / 3600);
                                        $minutes = floor(($diff % 3600) / 60);
                                        echo "{$hours}j {$minutes}m";
                                        ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($record['status'] === 'present'): ?>
                                        <span class="bg-success-soft text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">Hadir</span>
                                    <?php elseif ($record['status'] === 'late'): ?>
                                        <span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">Terlambat</span>
                                    <?php else: ?>
                                        <span class="bg-warning-soft text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded">Half Day</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($record['notes'] ?: '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Update jam real-time

    function updateTime() {
        const witaTime = new Date(now.toLocaleString('en-US', {
            timeZone: 'Asia/Makassar'
        }));
        const hours = String(witaTime.getHours()).padStart(2, '0');
        const minutes = String(witaTime.getMinutes()).padStart(2, '0');
        const seconds = String(witaTime.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    }
    updateTime();
    setInterval(updateTime, 1000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
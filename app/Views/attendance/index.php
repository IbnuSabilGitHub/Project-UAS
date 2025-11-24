<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Absensi Saya</h1>

    <?php if (!empty($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Status Hari Ini & Form Check-in/out -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Status Hari Ini -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Status Hari Ini</h2>
            <div class="mb-4">
                <p class="text-gray-700"><strong>Tanggal:</strong> <?= date('d F Y') ?></p>
                <p class="text-gray-700"><strong>Waktu Sekarang:</strong> <span id="currentTime"><?= date('H:i:s') ?></span></p>
            </div>
            
            <?php if ($todayStatus): ?>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="font-semibold text-blue-700">Check-in: <?= date('H:i:s', strtotime($todayStatus['check_in'])) ?></p>
                    <?php if ($todayStatus['check_out']): ?>
                        <p class="font-semibold text-green-700">Check-out: <?= date('H:i:s', strtotime($todayStatus['check_out'])) ?></p>
                    <?php else: ?>
                        <p class="text-gray-600 text-sm mt-2">Belum check-out</p>
                    <?php endif; ?>
                    
                    <?php if ($todayStatus['status'] === 'late'): ?>
                        <span class="inline-block mt-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Terlambat</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600">Anda belum check-in hari ini</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Form Check-in/out -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Absensi</h2>
            
            <?php if (!$todayStatus): ?>
                <!-- Form Check-in -->
                <form action="<?= url('/karyawan/attendance/checkin') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Catatan (opsional)</label>
                        <textarea name="notes" rows="3" 
                                  class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Masukkan catatan jika ada"></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-3 rounded transition duration-200">
                        Check-in Sekarang
                    </button>
                </form>
            <?php elseif (!$todayStatus['check_out']): ?>
                <!-- Form Check-out -->
                <form action="<?= url('/karyawan/attendance/checkout') ?>" method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Catatan (opsional)</label>
                        <textarea name="notes" rows="3" 
                                  class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Masukkan catatan jika ada"></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-3 rounded transition duration-200">
                        Check-out Sekarang
                    </button>
                </form>
            <?php else: ?>
                <div class="bg-green-50 p-4 rounded">
                    <p class="text-green-700 font-medium">✓ Anda sudah check-in dan check-out hari ini</p>
                    <p class="text-sm text-gray-600 mt-2">Terima kasih sudah absen tepat waktu!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistik Bulan Ini -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Statistik Bulan Ini</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-blue-600"><?= $stats['total_days'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">Total Hari Hadir</p>
            </div>
            <div class="bg-green-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-green-600"><?= $stats['on_time'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">Tepat Waktu</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-yellow-600"><?= $stats['late'] ?? 0 ?></p>
                <p class="text-sm text-gray-600">Terlambat</p>
            </div>
            <div class="bg-purple-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-purple-600">
                    <?= isset($stats['avg_hours']) && $stats['avg_hours'] ? number_format($stats['avg_hours'], 1) : '0' ?>
                </p>
                <p class="text-sm text-gray-600">Rata-rata Jam Kerja</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Absensi -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Riwayat Absensi (30 Hari Terakhir)</h2>
        
        <?php if (empty($history)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-gray-600">Belum ada riwayat absensi</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($history as $record): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('d/m/Y', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('H:i:s', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($record['check_out']): ?>
                                        <?= date('H:i:s', strtotime($record['check_out'])) ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Hadir</span>
                                    <?php elseif ($record['status'] === 'late'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Terlambat</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Half Day</span>
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

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="<?= url('/karyawan/dashboard') ?>" 
           class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded transition duration-200">
            Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
// Update jam real-time
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
}

setInterval(updateTime, 1000);
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">

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

    <h1 class="text-2xl font-bold mb-6 text-heading">Manajemen Absensi</h1>


    <div class="grid md:grid-cols-5 gap-4 mb-6">
        <div class="bg-brand-soft p-4 rounded-base border border-brand-medium">
            <p class="text-2xl font-bold text-brand-strong"><?= $stats['total_attendance'] ?? 0 ?></p>
            <p class="text-sm text-body">Total Absensi</p>
        </div>
        <div class="bg-neutral-primary-soft p-4 rounded-base border border-brand-medium">
            <p class="text-2xl font-bold text-green-600"><?= $stats['on_time'] ?? 0 ?></p>
            <p class="text-sm text-body">Tepat Waktu</p>
        </div>
        <div class="bg-neutral-primary-soft  p-4 rounded-base border border-brand-medium">
            <p class="text-2xl font-bold text-yellow-600"><?= $stats['late'] ?? 0 ?></p>
            <p class="text-sm text-body">Terlambat</p>
        </div>
        <div class="bg-neutral-primary-soft  p-4 rounded-base border border-brand-medium">
            <p class="text-2xl font-bold text-purple-600"><?= $stats['half_day'] ?? 0 ?></p>
            <p class="text-sm text-body">Half Day</p>
        </div>
        <div class="bg-neutral-primary-soft p-4 rounded-base border border-brand-medium">
            <p class="text-2xl font-bold text-red-600"><?= $stats['not_checkout'] ?? 0 ?></p>
            <p class="text-sm text-body">Belum Checkout</p>
        </div>
    </div>

    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
        <h2 class="text-xl font-semibold text-heading mb-4">Filter Data</h2>
        <form action="<?= url('/admin/attendance') ?>" method="GET" class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-heading mb-2">Tanggal</label>
                <input type="date" name="date" value="<?= htmlspecialchars($filters['date']) ?>"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
            </div>

            <div>
                <label class="block text-sm font-medium text-heading mb-2">Nama Karyawan</label>
                <input type="text" name="search_name" value="<?= htmlspecialchars($filters['search_name'] ?? '') ?>"
                    placeholder="Cari nama karyawan..."
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
            </div>
            

            <div>
                <label class="block text-sm font-medium text-heading mb-2">Status</label>
                <select name="status"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                    <option value="">Semua Status</option>
                    <option value="present" <?= $filters['status'] === 'present' ? 'selected' : '' ?>>Hadir</option>
                    <option value="late" <?= $filters['status'] === 'late' ? 'selected' : '' ?>>Terlambat</option>
                    <option value="half_day" <?= $filters['status'] === 'half_day' ? 'selected' : '' ?>>Half Day</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">
                    Filter
                </button>
                <a href="<?= url('/admin/attendance/export?' . http_build_query($filters)) ?>"
                    class="flex-1 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 shadow-xs font-medium rounded-base text-sm px-4 py-2.5 text-center">
                    Export CSV
                </a>
            </div>
        </form>

        <?php if ($filters['date'] || $filters['search_name'] || $filters['status']): ?>
            <div class="mt-4">
                <a href="<?= url('/admin/attendance') ?>"
                    class="text-sm text-brand hover:text-brand-strong font-medium">
                    ✕ Reset Filter
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="relative overflow-x-auto shadow-xs sm:rounded-base">
        <div class="overflow-x-auto">
            <?php if (empty($attendances)): ?>
                <div class="text-center py-12 bg-neutral-primary-soft">
                    <svg class="mx-auto h-12 w-12 text-neutral-tertiary-medium" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="mt-4 text-body">Tidak ada data absensi</p>
                </div>
            <?php else: ?>
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">NIK</th>
                            <th scope="col" class="px-6 py-3">Nama Karyawan</th>
                            <th scope="col" class="px-6 py-3">Jabatan</th>
                            <th scope="col" class="px-6 py-3">Check-in</th>
                            <th scope="col" class="px-6 py-3">Check-out</th>
                            <th scope="col" class="px-6 py-3">Durasi</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-neutral-primary-soft">
                        <?php foreach ($attendances as $record): ?>
                            <tr class="border-b border-default hover:bg-neutral-secondary-soft">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('d/m/Y', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?= htmlspecialchars($record['nik']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= htmlspecialchars($record['name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                    <?= htmlspecialchars($record['position']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('H:i:s', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($record['check_out']): ?>
                                        <?= date('H:i:s', strtotime($record['check_out'])) ?>
                                    <?php else: ?>
                                        <span class="text-neutral-tertiary-medium">-</span>
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
                                        <span class="text-neutral-tertiary-medium">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($record['status'] === 'present'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-medium">Hadir</span>
                                    <?php elseif ($record['status'] === 'late'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-medium">Terlambat</span>
                                    <?php elseif ($record['status'] === 'half_day'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800 font-medium">Half Day</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 font-medium"><?= $record['status'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-body max-w-xs truncate" title="<?= htmlspecialchars($record['notes'] ?? '') ?>">
                                    <?= htmlspecialchars($record['notes'] ?: '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?php if ($pagination['total'] > 1): ?>
            <div class="bg-neutral-secondary-soft px-6 py-4 border-t border-default flex items-center justify-between">
                <div class="text-sm text-body">
                    Menampilkan halaman <?= $pagination['current'] ?> dari <?= $pagination['total'] ?>
                    (Total: <?= $pagination['totalRecords'] ?> record)
                </div>
                <div class="flex gap-2">
                    <?php if ($pagination['current'] > 1): ?>
                        <a href="<?= url('/admin/attendance?' . http_build_query(array_merge($filters, ['page' => $pagination['current'] - 1]))) ?>"
                            class="px-4 py-2 bg-neutral-primary-soft border border-default rounded-base hover:bg-neutral-secondary-soft text-sm">
                            Sebelumnya
                        </a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['total'], $pagination['current'] + 2); $i++): ?>
                        <a href="<?= url('/admin/attendance?' . http_build_query(array_merge($filters, ['page' => $i]))) ?>"
                            class="px-4 py-2 border rounded-base text-sm <?= $i === $pagination['current'] ? 'bg-brand text-white border-brand' : 'bg-neutral-primary-soft border-default hover:bg-neutral-secondary-soft' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagination['current'] < $pagination['total']): ?>
                        <a href="<?= url('/admin/attendance?' . http_build_query(array_merge($filters, ['page' => $pagination['current'] + 1]))) ?>"
                            class="px-4 py-2 bg-neutral-primary-soft border border-default rounded-base hover:bg-neutral-secondary-soft text-sm">
                            Selanjutnya
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-8">
        <a href="<?= url('/admin/dashboard') ?>"
            class="inline-block text-brand hover:text-brand-strong font-medium">
            <i class="fa-solid fa-arrow-left-long mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
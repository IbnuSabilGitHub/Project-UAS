<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8 flex flex-col min-h-screen">

    <?php if (isset($_SESSION['success'])): ?>
        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="alert-danger" class="flex items-center p-4 mb-4 text-red-800 rounded-base bg-neutral-primary-soft" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <h1 class="text-2xl font-bold text-heading mb-6">Daftar Pengajuan Cuti</h1>

    <?php if (isset($statistics) && !empty($statistics)): ?>
        <div class="grid md:grid-cols-4 gap-4 mb-6">
            <div class="bg-neutral-primary-soft rounded-base shadow-xs p-4 border border-default">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-file text-2xl text-blue-600 w-8 h-8"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-body">Total Pengajuan</p>
                        <p class="text-2xl font-bold text-heading"><?= $statistics['total'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-neutral-primary-soft rounded-base shadow-xs p-4 border border-default">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-clock text-2xl text-yellow-600 w-8 h-8"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-body">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600"><?= $statistics['pending'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-neutral-primary-soft rounded-base shadow-xs p-4 border border-default">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-square-check text-2xl text-green-600 w-8 h-8"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-body">Approved</p>
                        <p class="text-2xl font-bold text-green-600"><?= $statistics['approved'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-neutral-primary-soft rounded-base shadow-xs p-4 border border-default">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-2xl text-red-600 w-8 h-8"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-body">Rejected</p>
                        <p class="text-2xl font-bold text-red-600"><?= $statistics['rejected'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex-1 overflow-y-auto overflow-x-auto shadow-md sm:rounded-base bg-neutral-secondary-soft p-4 space-y-4">
        <div>
            <form class="max-w-md mx-auto" action="" method="GET">
                <label for="search" class="block mb-2.5 text-sm font-medium text-heading sr-only ">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="search" class="block w-full p-3 ps-9 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" placeholder="Search" required />
                    <button type="button" class="absolute end-1.5 bottom-1.5 text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded text-xs px-3 py-1.5 focus:outline-none">Search</button>
                </div>
            </form>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Karyawan</th>
                    <th scope="col" class="px-6 py-3">NIK</th>
                    <th scope="col" class="px-6 py-3">Posisi</th>
                    <th scope="col" class="px-6 py-3">Tanggal Mulai</th>
                    <th scope="col" class="px-6 py-3">Tanggal Selesai</th>
                    <th scope="col" class="px-6 py-3">Durasi</th>
                    <th scope="col" class="px-6 py-3">Alasan</th>
                    <th scope="col" class="px-6 py-3">Dokumen</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Tanggal Pengajuan</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($pengajuanCuti)): ?>
                    <tr class="bg-neutral-primary-soft border-b border-default">
                        <td colspan="12" class="px-6 py-8 text-center text-body">
                            Belum ada data pengajuan cuti
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pengajuanCuti as $pc): ?>
                        <?php
                        $start = new DateTime($pc['start_date']);
                        $end = new DateTime($pc['end_date']);
                        $duration = $start->diff($end)->days + 1;

                        // Badge styling berdasarkan status
                        $statusClass = 'bg-neutral-secondary-soft text-body';
                        if ($pc['status'] === 'approved') {
                            $statusClass = 'bg-success-soft text-success-strong';
                        } elseif ($pc['status'] === 'rejected') {
                            $statusClass = 'bg-danger-soft text-danger-strong';
                        } elseif ($pc['status'] === 'pending') {
                            $statusClass = 'bg-warning-soft text-warning-strong';
                        }
                        ?>
                        <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150">
                            <td class="px-6 py-4"><?= htmlspecialchars($pc['id']) ?></td>
                            <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                <?= htmlspecialchars($pc['karyawan_name']) ?>
                            </th>
                            <td class="px-6 py-4"><?= htmlspecialchars($pc['nik']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($pc['position'] ?? '-') ?></td>
                            <td class="px-6 py-4"><?= date('d M Y', strtotime($pc['start_date'])) ?></td>
                            <td class="px-6 py-4"><?= date('d M Y', strtotime($pc['end_date'])) ?></td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-heading"><?= $duration ?> hari</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-body truncate" title="<?= htmlspecialchars($pc['reason']) ?>">
                                        <?= htmlspecialchars($pc['reason']) ?>
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($pc['document_path'])): ?>
                                    <a href="<?= url('/' . htmlspecialchars($pc['document_path'])) ?>"
                                        target="_blank"
                                        class="inline-flex items-center text-xs font-medium px-2.5 py-1.5 rounded-base bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Lihat PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-body">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full <?= $statusClass ?>">
                                    <?= htmlspecialchars(ucfirst($pc['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= date('d M Y H:i', strtotime($pc['created_at'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($pc['status'] === 'pending'): ?>
                                    <form action="<?= url('/admin/cuti/approve') ?>" method="post" class="inline mr-2">
                                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                        <button type="submit"
                                            class="text-success-strong hover:underline font-medium text-sm"
                                            onclick="return confirm('Setujui pengajuan cuti ini?');">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="<?= url('/admin/cuti/reject') ?>" method="post" class="inline mr-2">
                                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                        <button type="submit"
                                            class="text-danger-strong hover:underline font-medium text-sm"
                                            onclick="return confirm('Tolak pengajuan cuti ini?');">
                                            Reject
                                        </button>
                                    </form>
                                <?php endif; ?>



                                <form action="<?= url('/admin/cuti/delete') ?>" method="post" class="inline">
                                    <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                    <button type="submit"
                                        class="text-danger-strong hover:underline font-medium text-sm"
                                        onclick="return confirm('Hapus pengajuan cuti ini?');">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        <a href="<?= url('/admin/dashboard') ?>"
            class="inline-block text-brand hover:text-brand-strong font-medium">
            ← Kembali ke Dashboard
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
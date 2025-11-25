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

    <!-- Statistik pengajuan cuti  -->
    <?php if (isset($statistics) && !empty($statistics)): ?>
        <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
            <h2 class="text-xl font-semibold mb-4 text-heading">Statistik Pengajuan Cuti Karyawan</h2>

            <!-- Card -->
            <div class="grid md:grid-cols-4 gap-4 mb-6">
                <!-- Total Pengajuan (referensi) -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['total'] ?? 0 ?></p>
                    <p class="text-body">Total Pengajuan</p>
                </div>

                <!-- Pending -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['pending'] ?? 0 ?></p>
                    <p class="text-body">Pending</p>
                </div>

                <!-- Approved -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['approved'] ?? 0 ?></p>
                    <p class="text-body">Approved</p>
                </div>

                <!-- Rejected -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['rejected'] ?? 0 ?></p>
                    <p class="text-body">Rejected</p>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <div class="flex-1 overflow-y-auto overflow-x-auto shadow-md sm:rounded-base bg-neutral-secondary-soft p-4 space-y-4">
        <div class="flex flex-row items-center justify-between w-full">
            <h1 class="text-2xl font-bold text-heading">Daftar Pengajuan Cuti</h1>
            <form class="min-w-md " action="" method="GET">
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
        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">#</th>
                        <th scope="col" class="px-6 py-3 font-medium">Karyawan</th>
                        <th scope="col" class="px-6 py-3 font-medium">NIK</th>
                        <th scope="col" class="px-6 py-3 font-medium">Posisi</th>
                        <th scope="col" class="px-6 py-3 font-medium">Periode Cuti</th>
                        <th scope="col" class="px-6 py-3 font-medium">Durasi</th>
                        <th scope="col" class="px-6 py-3 font-medium">Alasan</th>
                        <th scope="col" class="px-6 py-3 font-medium">Dokumen</th>
                        <th scope="col" class="px-6 py-3 font-medium">Status</th>
                        <th scope="col" class="px-6 py-3 font-medium">Diajukan</th>
                        <th scope="col" class="px-6 py-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($pengajuanCuti)): ?>
                        <tr class="bg-neutral-primary border-b border-default">
                            <td colspan="11" class="px-6 py-4 text-center text-body">
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
                            $statusClass = 'text-xs font-medium px-1.5 py-0.5 rounded';
                            if ($pc['status'] === 'approved') {
                                $statusClass = 'bg-success-soft text-fg-success-strong';
                            } elseif ($pc['status'] === 'rejected') {
                                $statusClass = 'bg-danger-soft text-fg-danger-strong';
                            } elseif ($pc['status'] === 'pending') {
                                $statusClass = 'bg-warning-soft text-fg-warning';
                            }
                            ?>
                            <tr class="bg-neutral-primary border-b border-default">
                                <td class="px-6 py-4"><?= htmlspecialchars($pc['id']) ?></td>
                                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                    <?= htmlspecialchars($pc['karyawan_name']) ?>
                                </th>
                                <td class="px-6 py-4"><?= htmlspecialchars($pc['nik']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($pc['position'] ?? '-') ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-heading"><?= date('d/m/y', strtotime($pc['start_date'])) ?> - <?= date('d/m/y', strtotime($pc['end_date'])) ?></div>
                                        <div class="text-xs text-body"><?= date('M Y', strtotime($pc['start_date'])) ?></div>
                                    </div>
                                </td>
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
                                <td class="px-6 py-4">
                                    <div class="text-sm text-body">
                                        <div><?= date('d/m/y', strtotime($pc['created_at'])) ?></div>
                                        <div class="text-xs"><?= date('H:i', strtotime($pc['created_at'])) ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button id="dropdownButton<?= $pc['id'] ?>" data-dropdown-toggle="dropdown<?= $pc['id'] ?>"
                                        class="inline-flex items-center p-2 text-sm font-medium text-center text-heading bg-neutral-primary hover:bg-neutral-secondary-medium rounded-base focus:ring-4 focus:outline-none focus:ring-neutral-tertiary"
                                        type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                            <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="dropdown<?= $pc['id'] ?>" class="z-10 hidden bg-neutral-primary border border-default-medium rounded-base shadow-lg w-44">
                                        <ul class="py-2 text-sm text-body" aria-labelledby="dropdownButton<?= $pc['id'] ?>">
                                            <?php if ($pc['status'] === 'pending'): ?>
                                                <li>
                                                    <form action="<?= url('/admin/cuti/approve') ?>" method="post" class="block" onsubmit="return confirm('Setujui pengajuan cuti ini?');">
                                                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-success-strong font-medium flex items-center">
                                                            <i class="fa-solid fa-check text-xs mr-2"></i>
                                                            Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="<?= url('/admin/cuti/reject') ?>" method="post" class="block" onsubmit="return confirm('Tolak pengajuan cuti ini?');">
                                                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-danger-strong font-medium flex items-center">
                                                            <i class="fa-solid fa-x text-xs mr-2"></i>
                                                            Reject
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <hr class="my-1 border-default-medium">
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <button type="button"
                                                    onclick="alert('Detail: <?= htmlspecialchars($pc['reason']) ?>')"
                                                    class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-heading flex items-center">
                                                    <i class="fa-solid fa-eye text-xs mr-2"></i>
                                                    Detail
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="my-1 border-default-medium">
                                            </li>
                                            <li>
                                                <form action="<?= url('/admin/cuti/delete') ?>" method="post" class="block" onsubmit="return confirm('Hapus pengajuan cuti ini?');">
                                                    <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-danger-strong flex items-center">
                                                        <i class="fa-solid fa-trash text-xs mr-2"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-8">
        <a href="<?= url('/admin/dashboard') ?>"
            class="inline-block text-brand hover:text-brand-strong font-medium">
            <i class="fa-solid fa-arrow-left-long mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

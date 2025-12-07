<?php require_once __DIR__ . '/../../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../layouts/sidebar.php'; ?>

<div class="p-4 sm:ml-64 mt-14">

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

            <!-- Warpper filter -->
            <div class="flex flex-row space-x-4">
                <!-- Filter Tanggal (Last) -->
                <div>

                    <!-- Button dropdown periode -->
                    <button id="dropdownHelperRadioButton" data-dropdown-toggle="dropdownHelperRadio" class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
                        Last
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownHelperRadio" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownHelperRadioButton">
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-radio-7" name="date-filter-radio" type="radio" value="7"
                                            class="w-4 h-4 text-neutral-primary border-default-strong bg-neutral-secondary-strong rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none"
                                            <?= (isset($currentDateFilter) && $currentDateFilter === '7') ? 'checked' : '' ?>>
                                    </div>
                                    <div class="ms-2 text-sm">
                                        <label for="helper-radio-7" class="select-none font-medium text-heading">
                                            <div class="mb-1">7 Hari Terakhir</div>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-radio-30" name="date-filter-radio" type="radio" value="30"
                                            class="w-4 h-4 text-neutral-primary border-default-strong bg-neutral-secondary-strong rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none"
                                            <?= (isset($currentDateFilter) && $currentDateFilter === '30') ? 'checked' : '' ?>>
                                    </div>
                                    <div class="ms-2 text-sm">
                                        <label for="helper-radio-30" class="select-none font-medium text-heading">
                                            <div class="mb-1">30 Hari Terakhir</div>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-radio-60" name="date-filter-radio" type="radio" value="60"
                                            class="w-4 h-4 text-neutral-primary border-default-strong bg-neutral-secondary-strong rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none"
                                            <?= (isset($currentDateFilter) && $currentDateFilter === '60') ? 'checked' : '' ?>>
                                    </div>
                                    <div class="ms-2 text-sm">
                                        <label for="helper-radio-60" class="select-none font-medium text-heading">
                                            <div class="mb-1">60 Hari Terakhir</div>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-radio-all" name="date-filter-radio" type="radio" value=""
                                            class="w-4 h-4 text-neutral-primary border-default-strong bg-neutral-secondary-strong rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none"
                                            <?= (empty($currentDateFilter)) ? 'checked' : '' ?>>
                                    </div>
                                    <div class="ms-2 text-sm">
                                        <label for="helper-radio-all" class="select-none font-medium text-heading">
                                            <div class="mb-1">Semua</div>
                                        </label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Status (Checkbox) -->
                <div>
                    <button id="dropdownHelperCheckboxButton"
                        data-dropdown-toggle="dropdownHelperCheckbox"
                        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
                        type="button">
                        Status
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownHelperCheckbox"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownHelperCheckboxButton">

                            <!-- Approved -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-checkbox-1" type="checkbox" value="approved" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('approved', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="helper-checkbox-1" class="ms-2 text-sm select-none font-medium text-heading">
                                        Approved
                                    </label>
                                </div>
                            </li>

                            <!-- Pending -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-checkbox-2" type="checkbox" value="pending" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('pending', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="helper-checkbox-2" class="ms-2 text-sm select-none font-medium text-heading">
                                        Pending
                                    </label>
                                </div>
                            </li>

                            <!-- Rejected -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-checkbox-3" type="checkbox" value="rejected" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('rejected', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="helper-checkbox-3" class="ms-2 text-sm select-none font-medium text-heading">
                                        Rejected
                                    </label>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
                <!-- Search berdasarkan nama karyawan -->
                <form class="min-w-xs flex space-x-2" id="searchForm" method="GET">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-user text-sm text-body"></i>
                        </div>
                        <input type="text" name="search" id="simple-search" value="<?= htmlspecialchars($currentSearch ?? '') ?>" class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base ps-9 text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Cari nama karyawan..." />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center shrink-0 text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs rounded-base w-10 h-10 focus:outline-none">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Icon description</span>
                    </button>
                </form>
            </div>
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
                        <th scope="col" class="px-6 py-3 font-medium">
                            Durasi
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">Alasan</th>
                        <th scope="col" class="px-6 py-3 font-medium">Dokumen</th>
                        <th scope="col" class="px-6 py-3 font-medium">Status</th>
                        <th scope="col" class="px-6 py-3 font-medium">Diajukan</th>
                        <th scope="col" class="px-6 py-3 font-medium text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($pengajuanCuti)): ?>
                        <!-- Ui Jika data pengajuan cuti kosong -->
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
                                    <?php if (!empty($pc['attachment_file'])): ?>
                                        <a href="<?= url('/file/leave/' . htmlspecialchars($pc['id'])) ?>"
                                            target="_blank"
                                            class="inline-flex items-center text-xs font-medium px-2.5 py-1.5 rounded-base bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            Lihat File
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
                                                    <form
                                                        action="<?= url('/admin/cuti/approve') ?>"
                                                        method="post"
                                                        class="block"
                                                        id="approve-form-<?= $pc['id'] ?>"
                                                        onsubmit="return handleApprove(event, '<?= $pc['id'] ?>', '<?= htmlspecialchars(addslashes($pc['karyawan_name'])) ?>');">
                                                        <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-success-strong font-medium flex items-center">
                                                            <i class="fa-solid fa-check text-xs mr-2"></i>
                                                            Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                        data-modal-target="reject-modal-<?= $pc['id'] ?>"
                                                        data-modal-toggle="reject-modal-<?= $pc['id'] ?>"
                                                        class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-danger-strong font-medium flex items-center">
                                                        <i class="fa-solid fa-xmark text-xs mr-2"></i>
                                                        Reject
                                                    </button>
                                                </li>
                                                <li>
                                                    <hr class="my-1 border-default-medium">
                                                </li>
                                            <?php endif; ?>
                                            <li>
                                                <button type="button"
                                                    title="<?= htmlspecialchars($pc['reason']) ?>"
                                                    onclick="showDetailInfo('<?= htmlspecialchars($pc['karyawan_name']) ?>', '<?= htmlspecialchars($pc['reason']) ?>', '<?= date('d/m/Y', strtotime($pc['start_date'])) ?> - <?= date('d/m/Y', strtotime($pc['end_date'])) ?>', '<?= $duration ?>')"
                                                    class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-heading flex items-center">
                                                    <i class="fa-solid fa-eye text-xs mr-2"></i>
                                                    Detail
                                                </button>
                                            </li>
                                            <li>
                                                <hr class="my-1 border-default-medium">
                                            </li>
                                            <li>
                                                <form
                                                    action="<?= url('/admin/cuti/delete') ?>"
                                                    method="post"
                                                    class="block"
                                                    id="delete-leave-form-<?= $pc['id'] ?>"
                                                    onsubmit="return handleDeleteLeave(event, <?= $pc['id'] ?>, '<?= htmlspecialchars($pc['karyawan_name']) ?>');">
                                                    <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-neutral-tertiary-medium text-danger-strong flex items-center">
                                                        <i class="fa-solid fa-trash text-xs mr-2"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Rejection Modal -->
                                    <div id="reject-modal-<?= $pc['id'] ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                            <!-- Modal content -->
                                            <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
                                                <!-- Modal header -->
                                                <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                                                    <h3 class="text-lg font-semibold text-heading">
                                                        <i class="fa-solid fa-triangle-exclamation text-danger-strong inline-block mr-2"></i>
                                                        Tolak Pengajuan Cuti
                                                    </h3>
                                                    <button type="button"
                                                        class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                                                        data-modal-hide="reject-modal-<?= $pc['id'] ?>">
                                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                </div>
                                                <!-- Modal body -->
                                                <form
                                                    action="<?= url('/admin/cuti/reject') ?>"
                                                    method="POST"
                                                    id="reject-form-<?= $pc['id'] ?>"
                                                    onsubmit="return handleReject(event, '<?= $pc['id'] ?>', '<?= htmlspecialchars($pc['karyawan_name']) ?>')">
                                                    <input type="hidden" name="id" value="<?= $pc['id'] ?>">
                                                    <div class="py-4 md:py-6">
                                                        <!-- Info Karyawan -->
                                                        <div class="bg-neutral-secondary-soft border border-default-medium rounded-base p-3 mb-4 flex flex-col items-start">
                                                            <p class="text-sm text-body aligin"><strong class="text-heading">Karyawan:</strong> <?= htmlspecialchars($pc['karyawan_name']) ?></p>
                                                            <p class="text-sm text-body aligin"><strong class="text-heading">Periode:</strong> <?= date('d/m/y', strtotime($pc['start_date'])) ?> - <?= date('d/m/y', strtotime($pc['end_date'])) ?></p>
                                                        </div>

                                                        <!-- Alasan Penolakan -->
                                                        <div class="mb-4">
                                                            <label for="rejection_reason_<?= $pc['id'] ?>" class="block mb-2.5 text-sm font-medium text-heading">
                                                                Alasan Penolakan <span class="text-danger-strong">*</span>
                                                            </label>
                                                            <textarea
                                                                id="rejection_reason_<?= $pc['id'] ?>"
                                                                name="rejection_reason"
                                                                rows="4"
                                                                required
                                                                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand w-full p-3.5 shadow-xs placeholder:text-body"
                                                                placeholder="Jelaskan alasan penolakan cuti ini kepada karyawan..."></textarea>

                                                            <div class="flex items-start sm:items-center py-2 px-4 mb-2 mt-2 text-sm text-fg-warning rounded-base bg-warning-soft" role="alert">
                                                                <svg class="w-4 h-4 me-2 shrink-0 mt-0.5 sm:mt-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                                </svg>
                                                                <p>Alasan ini akan dikirimkan kepada karyawa</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="w-full text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                                        Tolak Pengajuan
                                                    </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function handleApprove(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'approve',
            title: 'Konfirmasi Approve',
            message: `Apakah Anda yakin menyetujui pengajuan cuti dari ${employeeName}?`,
            confirmText: 'Setujui',
            cancelText: 'Batal',
            onConfirm: () => {
                document.getElementById(`approve-form-${employeeId}`).submit();
            }
        });

        return false;
    }

    function handleReject(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'reject',
            title: 'Konfirmasi Reject',
            message: `Apakah Anda yakin menolak pengajuan cuti dari ${employeeName}?`,
            confirmText: 'Tolak',
            cancelText: 'Batal',
            onConfirm: () => {
                document.getElementById(`reject-form-${employeeId}`).submit();
            }
        });

        return false;
    }



    function handleDeleteLeave(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'delete',
            title: 'Hapus Pengajuan Cuti',
            message: `Apakah Anda yakin ingin menghapus pengajuan cuti untuk karyawan "${employeeName}" - "${employeeId}"?`,
            confirmText: 'Hapus',
            cancelText: 'Batal',
            onConfirm: function() {
                document.getElementById(`delete-leave-form-${employeeId}`).submit();
            }
        });
        return false;
    }

    function showDetailInfo(karyawan, reason, periode, durasi) {
        const message = `
        Karyawan: ${karyawan}
        Periode: ${periode}
        Durasi: ${durasi} hari

        Alasan:
        ${reason}
            `;
        alert(message);
    }

    // javaScript untuk menangani filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const statusCheckboxes = document.querySelectorAll('input[name="status-filter"]');
        const dateRadios = document.querySelectorAll('input[name="date-filter-radio"]');

        // Fungsi untuk menerapkan filter
        function applyFilters() {
            const url = new URL(window.location.href);
            url.search = ''; // Clear existing params

            // Ambil nilai pencarian
            const searchValue = document.getElementById('simple-search').value.trim();
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            }

            // Get checked statuses
            const checkedStatuses = Array.from(statusCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (checkedStatuses.length > 0 && checkedStatuses.length < 3) {
                checkedStatuses.forEach(status => {
                    url.searchParams.append('status[]', status);
                });
            }

            // Ambil filter tanggal yang dipilih
            const selectedDate = document.querySelector('input[name="date-filter-radio"]:checked');
            if (selectedDate && selectedDate.value) {
                url.searchParams.set('date_filter', selectedDate.value);
            }

            // Redirect dengan filter
            window.location.href = url.toString();
        }


        statusCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });

        // deteksi perubahan pada radio button tanggal
        dateRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                applyFilters();
            });
        });

        // deteksi submit form pencarian
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                applyFilters();
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
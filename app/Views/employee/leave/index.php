<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="p-4 sm:ml-64 mt-14">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-heading">Riwayat Cuti Saya</h1>
        <a href="<?= url('/karyawan/leave/create') ?>"
            class="inline-flex items-center text-white bg-brand hover:bg-brand-strong box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            <i class="fa-solid fa-circle-plus text-lg w-4 h-4 me-2.5 -ms-0.5"></i>
            Ajukan Cuti Baru
        </a>
    </div>

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

    <!-- Summary -->
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
        <h2 class="text-xl font-semibold mb-4 text-heading">Ringkasan Cuti Tahun Ini</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                <p class="mb-2 text-2xl font-semibold tracking-tight text-heading "><?= $totalApproved ?></p>
                <p class="text-body">Total Cuti Disetujui</p>
            </div>
            <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $totalRejected ?></p>
                <p class="text-body">Total Cuti Ditolak</p>
            </div>
            <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default ">
                <p class="mb-2 text-2xl font-semibold tracking-tight text-heading">
                    <?= count(array_filter($leaves, function ($l) {
                        return $l['status'] === 'pending';
                    })) ?>
                </p>
                <p class="text-body">Menunggu Persetujuan</p>
            </div>
        </div>
    </div>

    <!-- Daftar Pengajuan Cuti -->
    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
        <h2 class="text-xl font-semibold mb-4 text-heading">Daftar Pengajuan Cuti</h2>

        <?php if (empty($leaves)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-gray-600">Belum ada pengajuan cuti</p>
                <a href="<?= url('/karyawan/leave/create') ?>"
                    class="mt-4 inline-block text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-5 py-2.5">
                    Ajukan Cuti Sekarang
                </a>
            </div>
        <?php else: ?>

            <div class="relative overflow-x-auto shadow-xs rounded-base border border-default" style="max-height: 600px;">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-neutral-secondary-soft border-b border-default sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">No</th>
                            <th scope="col" class="px-6 py-3 font-medium">Jenis Cuti</th>
                            <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                            <th scope="col" class="px-6 py-3 font-medium">Durasi</th>
                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                            <th scope="col" class="px-6 py-3 font-medium">Lampiran</th>
                            <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($leaves as $index => $leave): ?>
                            <?php
                            $types = [
                                'annual' => 'Tahunan',
                                'sick' => 'Sakit',
                                'emergency' => 'Darurat',
                                'unpaid' => 'Tidak Dibayar'
                            ];

                            $startDateValid = $leave['start_date'] && $leave['start_date'] !== '0000-00-00';
                            $endDateValid = $leave['end_date'] && $leave['end_date'] !== '0000-00-00';
                            ?>

                            <tr class="bg-neutral-primary border-b border-default">
                                <!-- No -->
                                <td class="px-6 py-4">
                                    <?= $index + 1 ?>
                                </td>

                                <!-- Jenis Cuti -->
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($types[$leave['leave_type']]) ?>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-heading"><?= date('d/m/y', strtotime($leave['start_date'])) ?> - <?= date('d/m/y', strtotime($leave['end_date'])) ?></div>
                                    <div class="text-xs text-body"><?= date('M Y', strtotime($leave['start_date'])) ?></div>
                                </td>

                                <!-- Total hari -->
                                <td class="px-6 py-4">
                                    <?= $leave['total_days'] ?> hari
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <span class="bg-warning-soft text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded">Pending</span>
                                    <?php elseif ($leave['status'] === 'approved'): ?>
                                        <span class="bg-success-soft text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">Disetujui</span>
                                    <?php else: ?>
                                        <span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">Ditolak</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Lampiran -->
                                <td class="px-6 py-4">
                                    <?php if ($leave['attachment_file']): ?>
                                        <a href="<?= url('/file/leave/' . $leave['id']) ?>"
                                            target="_blank"
                                            class="text-blue-600 hover:text-blue-800">
                                            Lihat File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <form
                                            action="<?= url('/karyawan/leave/delete') ?>"
                                            method="POST"
                                            id="cancel-form-<?= $leave['id'] ?>"
                                            onsubmit="return handleCancelLeave(event, <?= $leave['id'] ?>)">
                                            <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Batalkan
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button" class="text-blue-600 hover:text-blue-800"
                                            onclick="showDetail(<?= htmlspecialchars(json_encode($leave)) ?>)">
                                            Detail
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Overlay -->
<div id="modalOverlay"
    class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity duration-200 ">
</div>

<!-- Modal Container -->
<div id="detailModal"
    class="fixed inset-0 z-50 hidden flex items-start justify-center overflow-y-auto p-6 mt-14">
    <div id="modalBox"
        class="bg-neutral-primary-soft border border-default rounded-base shadow-lg w-full max-w-xl
               opacity-0 scale-95 transition-all duration-200 mt-20">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-default p-4">
            <h5 class="flex items-center text-lg font-semibold text-heading">
                <svg class="w-5 h-5 mr-2" aria-hidden="true" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Detail Pengajuan Cuti
            </h5>
            <button type="button" onclick="closeModal()"
                class="w-9 h-9 flex items-center justify-center rounded-base text-body
                       hover:bg-neutral-tertiary hover:text-heading transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>

        <!-- Body (auto-filled) -->
        <div id="modalContent" class="p-4 space-y-4 text-body leading-relaxed"></div>

    </div>
</div>




<script>
    function handleCancelLeave(event, employeeId) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'delete',
            title: 'Konfirmasi Cancel',
            message: `Apakah Anda yakin membatalkan pengajuan cuti ini?`,
            confirmText: 'Batalkan',
            cancelText: 'Batal',
            onConfirm: () => {
                document.getElementById(`cancel-form-${employeeId}`).submit();
            }
        });

        return false;
    }


    function showDetail(leave) {
        const modal = document.getElementById('detailModal');
        const overlay = document.getElementById('modalOverlay');
        const box = document.getElementById('modalBox');

        // === generate content (copy dari kode Anda) ===
        const types = {
            'annual': 'Tahunan',
            'sick': 'Sakit',
            'emergency': 'Darurat',
            'unpaid': 'Tidak Dibayar'
        };

        const status = {
            'pending': '<span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded-full">Pending</span>',
            'approved': '<span class="bg-success-soft text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded-full">Disetujui</span>',
            'rejected': '<span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded-full">Ditolak</span>'
        };

        let content = `
        <div class="flow-root">
        <dl class="-my-3 divide-y divide-default/40 text-sm">
            
            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Jenis Cuti</dt>
            <dd class="text-body sm:col-span-2">${types[leave.leave_type]}</dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Tanggal Mulai</dt>
            <dd class="text-body sm:col-span-2">${formatDate(leave.start_date)}</dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Tanggal Selesai</dt>
            <dd class="text-body sm:col-span-2">${formatDate(leave.end_date)}</dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Durasi</dt>
            <dd class="text-body sm:col-span-2">${leave.total_days} hari</dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Status</dt>
            <dd class="sm:col-span-2">
                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-base 
                        ${leave.status === 'approved'
                            ? 'bg-success-soft text-success-strong'
                            : leave.status === 'rejected'
                                ? 'bg-danger-soft text-danger-strong'
                                : 'bg-warning-soft text-warning-strong'}">
                    ${status[leave.status]}
                </span>
            </dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Alasan</dt>
            <dd class="text-body sm:col-span-2 leading-relaxed">${leave.reason}</dd>
            </div>

            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Diproses Oleh</dt>
            <dd class="text-body sm:col-span-2">${leave.approver_email || '-'}</dd>
            </div>
        `;


        if (leave.approved_by) {
            content += `
            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Diproses Oleh</dt>
            <dd class="text-body sm:col-span-2">${leave.approver_email || '-'}</dd>
            </div>
        `;
        }


        if (leave.rejection_reason) {
            content += `
            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Alasan Penolakan</dt>
            <dd class="text-body sm:col-span-2 leading-relaxed">
                <span class="text-body">${leave.rejection_reason}</span>
            </dd>
            </div>
        `;
        }


        if (leave.attachment_file) {
            content += `
            <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
            <dt class="font-medium text-heading">Lampiran</dt>
            <dd class="text-body sm:col-span-2">
                <a href="<?= url('/file/leave/') ?>${leave.id}"
                target="_blank"
                class="inline-flex items-center text-brand hover:text-brand-strong underline">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                Lihat File
                </a>
            </dd>
            </div>
        `;
        }

        content += `</dl></div>`;
        document.getElementById('modalContent').innerHTML = content;

        // === OPEN MODAL ===
        modal.classList.remove("hidden");
        overlay.classList.remove("hidden");

        setTimeout(() => {
            overlay.classList.remove("opacity-0");
            box.classList.remove("opacity-0", "scale-95");
        }, 10);

        document.body.style.overflow = "hidden"; // lock body scroll
    }

    function closeModal() {
        const modal = document.getElementById('detailModal');
        const overlay = document.getElementById('modalOverlay');
        const box = document.getElementById('modalBox');

        // animate reverse
        overlay.classList.add("opacity-0");
        box.classList.add("opacity-0", "scale-95");

        setTimeout(() => {
            modal.classList.add("hidden");
            overlay.classList.add("hidden");
        }, 200);

        document.body.style.overflow = ""; // unlock body scroll
    }

    // Close via overlay
    document.getElementById('modalOverlay').addEventListener('click', closeModal);

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
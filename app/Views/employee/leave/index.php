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
                <p class="mb-2 text-2xl font-semibold tracking-tight text-heading "><?= $totalDaysUsed ?></p>
                <p class="text-body">Hari Cuti Terpakai</p>
            </div>
            <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= max(0, 12 - $totalDaysUsed) ?></p>
                <p class="text-body">Hari Cuti Tersisa</p>
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

            <div class="orelative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-neutral-secondary-softborder-b rounded-base border-default">
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
                            <tr class="bg-neutral-primary border-b border-default">
                                <td class="px-6 py-4">
                                    <?= $index + 1 ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $types = [
                                        'annual' => 'Tahunan',
                                        'sick' => 'Sakit',
                                        'emergency' => 'Darurat',
                                        'unpaid' => 'Tidak Dibayar'
                                    ];
                                    echo $types[$leave['leave_type']] ?? $leave['leave_type'];
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                    $startDateValid = $leave['start_date'] && $leave['start_date'] !== '0000-00-00';
                                    $endDateValid = $leave['end_date'] && $leave['end_date'] !== '0000-00-00';
                                    ?>
                                    <div>
                                        <?= $startDateValid ? date('d/m/Y', strtotime($leave['start_date'])) : '<span class="text-red-500">Tanggal tidak valid</span>' ?>
                                    </div>
                                    <div class="text-gray-500">
                                        s/d <?= $endDateValid ? date('d/m/Y', strtotime($leave['end_date'])) : '<span class="text-red-500">Tanggal tidak valid</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $leave['total_days'] ?> hari
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <span class="bg-warning-soft text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded">Pending</span>
                                    <?php elseif ($leave['status'] === 'approved'): ?>
                                        <span class="bg-success-soft text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">Disetujui</span>
                                    <?php else: ?>
                                        <span class="bg-danger-soft text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">Ditolak</span>
                                    <?php endif; ?>
                                </td>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <form action="<?= url('/karyawan/leave/delete') ?>" method="POST"
                                            onsubmit="return confirm('Yakin ingin membatalkan pengajuan cuti ini?')">
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

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Detail Pengajuan Cuti</h3>
            <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="modalContent" class="space-y-3">
            <!-- Content will be filled by JavaScript -->
        </div>
    </div>
</div>

<script>
    function showDetail(leave) {
        const types = {
            'annual': 'Tahunan',
            'sick': 'Sakit',
            'emergency': 'Darurat',
            'unpaid': 'Tidak Dibayar'
        };

        const status = {
            'pending': '<span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>',
            'approved': '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Disetujui</span>',
            'rejected': '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>'
        };

        let content = `
        <div><strong>Jenis Cuti:</strong> ${types[leave.leave_type]}</div>
        <div><strong>Tanggal Mulai:</strong> ${formatDate(leave.start_date)}</div>
        <div><strong>Tanggal Selesai:</strong> ${formatDate(leave.end_date)}</div>
        <div><strong>Durasi:</strong> ${leave.total_days} hari</div>
        <div><strong>Status:</strong> ${status[leave.status]}</div>
        <div><strong>Alasan:</strong> ${leave.reason}</div>
    `;

        if (leave.approved_by) {
            content += `<div><strong>Diproses oleh:</strong> ${leave.approver_email || '-'}</div>`;
        }

        if (leave.rejection_reason) {
            content += `<div class="bg-red-50 p-3 rounded"><strong>Alasan Penolakan:</strong> ${leave.rejection_reason}</div>`;
        }

        if (leave.attachment_file) {
            content += `<div><strong>Lampiran:</strong> <a href="<?= url('/file/leave/') ?>${leave.id}" target="_blank" class="text-blue-600">Lihat File</a></div>`;
        }

        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    }

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
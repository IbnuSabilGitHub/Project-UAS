<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Riwayat Cuti Saya</h1>
        <a href="/karyawan/leave/create" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition duration-200">
            + Ajukan Cuti Baru
        </a>
    </div>

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

    <!-- Summary -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Ringkasan Cuti Tahun Ini</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-blue-600"><?= $totalDaysUsed ?></p>
                <p class="text-sm text-gray-600">Hari Cuti Terpakai</p>
            </div>
            <div class="bg-green-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-green-600"><?= max(0, 12 - $totalDaysUsed) ?></p>
                <p class="text-sm text-gray-600">Hari Cuti Tersisa</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded text-center">
                <p class="text-3xl font-bold text-yellow-600">
                    <?= count(array_filter($leaves, function($l) { return $l['status'] === 'pending'; })) ?>
                </p>
                <p class="text-sm text-gray-600">Menunggu Persetujuan</p>
            </div>
        </div>
    </div>

    <!-- Daftar Pengajuan Cuti -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Pengajuan Cuti</h2>
        
        <?php if (empty($leaves)): ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-gray-600">Belum ada pengajuan cuti</p>
                <a href="/karyawan/leave/create" 
                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition duration-200">
                    Ajukan Cuti Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Cuti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lampiran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($leaves as $index => $leave): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= $index + 1 ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                    <div><?= date('d/m/Y', strtotime($leave['start_date'])) ?></div>
                                    <div class="text-gray-500">s/d <?= date('d/m/Y', strtotime($leave['end_date'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= $leave['total_days'] ?> hari
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                                    <?php elseif ($leave['status'] === 'approved'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Disetujui</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($leave['attachment_file']): ?>
                                        <a href="/uploads/leave_attachments/<?= htmlspecialchars($leave['attachment_file']) ?>" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800">
                                            📎 Lihat File
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($leave['status'] === 'pending'): ?>
                                        <form action="/karyawan/leave/delete" method="POST" 
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

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="/karyawan/dashboard" 
           class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded transition duration-200">
            Kembali ke Dashboard
        </a>
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
        content += `<div><strong>Diproses oleh:</strong> ${leave.approver_name || '-'}</div>`;
    }
    
    if (leave.rejection_reason) {
        content += `<div class="bg-red-50 p-3 rounded"><strong>Alasan Penolakan:</strong> ${leave.rejection_reason}</div>`;
    }
    
    if (leave.attachment_file) {
        content += `<div><strong>Lampiran:</strong> <a href="/uploads/leave_attachments/${leave.attachment_file}" target="_blank" class="text-blue-600">📎 Lihat File</a></div>`;
    }
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

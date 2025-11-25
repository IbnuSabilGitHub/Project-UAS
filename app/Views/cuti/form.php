<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4 text-heading"><?= $title ?></h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="alert-danger" class="flex items-center p-4 mb-4 text-red-800 rounded-base bg-red-50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="max-w-4xl bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
        <?php
        $pc = $pengajuan ?? null;
        $isEdit = !empty($pc);
        ?>

        <form action="<?= $isEdit ? url('/admin/cuti/update') : url('/admin/cuti/store') ?>" method="post" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($pc['id']) ?>">
            <?php endif; ?>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-3">
                    <label for="karyawan_id" class="block mb-2.5 text-sm font-medium text-heading">Karyawan <span class="text-red-500">*</span></label>
                    <select name="karyawan_id" id="karyawan_id" 
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow"
                            required>
                        <option value="">-- Pilih Karyawan --</option>
                        <?php foreach ($karyawans as $k): ?>
                            <option value="<?= $k['id'] ?>" 
                                    <?= (isset($pc['karyawan_id']) && $pc['karyawan_id'] == $k['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['nik']) ?> - <?= htmlspecialchars($k['name']) ?> (<?= htmlspecialchars($k['position'] ?? '-') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="leave_type" class="block mb-2.5 text-sm font-medium text-heading">Jenis Cuti <span class="text-red-500">*</span></label>
                    <select name="leave_type" id="leave_type" 
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow"
                            required>
                        <option value="annual" <?= (isset($pc['leave_type']) && $pc['leave_type'] === 'annual') ? 'selected' : '' ?>>Cuti Tahunan</option>
                        <option value="sick" <?= (isset($pc['leave_type']) && $pc['leave_type'] === 'sick') ? 'selected' : '' ?>>Cuti Sakit</option>
                        <option value="emergency" <?= (isset($pc['leave_type']) && $pc['leave_type'] === 'emergency') ? 'selected' : '' ?>>Cuti Darurat</option>
                        <option value="unpaid" <?= (isset($pc['leave_type']) && $pc['leave_type'] === 'unpaid') ? 'selected' : '' ?>>Cuti Tanpa Gaji</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="block mb-2.5 text-sm font-medium text-heading">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input name="start_date" id="start_date" type="date" 
                           value="<?= htmlspecialchars($pc['start_date'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="end_date" class="block mb-2.5 text-sm font-medium text-heading">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input name="end_date" id="end_date" type="date" 
                           value="<?= htmlspecialchars($pc['end_date'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="status" class="block mb-2.5 text-sm font-medium text-heading">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" 
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow"
                            required>
                        <option value="pending" <?= (isset($pc['status']) && $pc['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= (isset($pc['status']) && $pc['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= (isset($pc['status']) && $pc['status'] === 'rejected') ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>

                <div class="mb-3 md:col-span-2">
                    <label for="reason" class="block mb-2.5 text-sm font-medium text-heading">Alasan Cuti <span class="text-red-500">*</span></label>
                    <textarea name="reason" id="reason" rows="5" 
                              class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body" 
                              placeholder="Masukkan alasan pengajuan cuti..."
                              required><?= htmlspecialchars($pc['reason'] ?? '') ?></textarea>
                    <p class="mt-2 text-sm text-body">Jelaskan alasan pengajuan cuti secara detail</p>
                </div>

                <div class="mb-3 md:col-span-2">
                    <label for="document" class="block mb-2.5 text-sm font-medium text-heading">
                        Dokumen Pendukung (PDF/Gambar)
                        <?php if ($isEdit && !empty($pc['attachment_file'])): ?>
                            <span class="text-green-600 text-xs ml-2">✓ File sudah diupload</span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($isEdit && !empty($pc['attachment_file'])): ?>
                        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-base">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm text-blue-800">Dokumen saat ini: </span>
                                    <a href="<?= url('/uploads/leave_attachments/' . htmlspecialchars($pc['attachment_file'])) ?>" 
                                       target="_blank"
                                       class="text-sm text-blue-600 hover:underline ml-1 font-medium">
                                        <?= htmlspecialchars($pc['attachment_file']) ?>
                                    </a>
                                </div>
                                <span class="text-xs text-blue-600">Upload file baru untuk mengganti</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="file" 
                           name="document" 
                           id="document" 
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                    <p class="mt-2 text-sm text-body">
                        <span class="font-medium">Opsional.</span> Upload surat keterangan dokter, undangan, atau dokumen pendukung lainnya (PDF/JPG/PNG, maksimal 5MB)
                    </p>
                </div>
            </div>

            <div class="mt-8 flex items-center">
                <button type="submit" 
                        class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    <?= $isEdit ? 'Perbarui' : 'Simpan' ?>
                </button>
                
                <a href="<?= url('/admin/cuti') ?>" 
                   class="ml-3 text-body text-sm font-medium hover:text-heading transition duration-150 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 max-w-4xl">
        <div class="bg-blue-50 border border-blue-200 rounded-base p-4">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">Informasi:</h3>
            <p class="text-sm text-blue-700 space-y-1 list-disc list-inside">Fitur ini hanya untuk sementara untuk demonstrasi feature tambah pengajuan cuti (karena pengajuan cuti di bagian karyawan masih dalam pengembangan)</p>
            <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                <li>Pilih karyawan yang akan mengajukan cuti</li>
                <li>Pastikan tanggal selesai tidak lebih awal dari tanggal mulai</li>
                <li>Status default adalah "Pending", dapat diubah sesuai kebutuhan</li>
                <li>Durasi cuti akan dihitung otomatis (termasuk hari pertama dan terakhir)</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Auto-calculate duration when dates are selected
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Optional: show duration (could add a display element)
            console.log('Duration:', diffDays, 'days');
        }
    }
    
    if (startDate && endDate) {
        startDate.addEventListener('change', calculateDuration);
        endDate.addEventListener('change', calculateDuration);
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

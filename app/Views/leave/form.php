<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-3xl font-bold mb-6">Ajukan Cuti</h1>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="<?= url('/karyawan/leave/store') ?>" method="POST" enctype="multipart/form-data" id="leaveForm">
            <!-- Jenis Cuti -->
            <div class="mb-4">
                <label for="leave_type" class="block text-gray-700 font-medium mb-2">
                    Jenis Cuti <span class="text-red-500">*</span>
                </label>
                <select name="leave_type" id="leave_type" required
                        class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Jenis Cuti --</option>
                    <option value="annual">Cuti Tahunan</option>
                    <option value="sick">Cuti Sakit</option>
                    <option value="emergency">Cuti Darurat</option>
                    <option value="unpaid">Cuti Tidak Dibayar</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 font-medium mb-2">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" required
                       min="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tanggal Selesai -->
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 font-medium mb-2">
                    Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" required
                       min="<?= date('Y-m-d') ?>"
                       class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Total Hari (auto calculate) -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Total Hari</label>
                <div class="bg-gray-50 border border-gray-300 p-3 rounded">
                    <span id="totalDays" class="text-lg font-semibold text-blue-600">0 hari</span>
                </div>
            </div>

            <!-- Alasan -->
            <div class="mb-4">
                <label for="reason" class="block text-gray-700 font-medium mb-2">
                    Alasan Cuti <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="4" required
                          class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Jelaskan alasan pengajuan cuti"></textarea>
            </div>

            <!-- Upload File -->
            <div class="mb-4">
                <label for="attachment" class="block text-gray-700 font-medium mb-2">
                    Lampiran File (Opsional)
                </label>
                <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png"
                       class="border border-gray-300 p-3 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-2">
                    Format: PDF, JPG, PNG. Maksimal 5MB. 
                    <br>Untuk cuti sakit, lampirkan surat keterangan dokter.
                </p>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <strong>Catatan:</strong> Pengajuan cuti akan diproses oleh admin. 
                    Anda akan menerima notifikasi setelah pengajuan disetujui atau ditolak.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-3 rounded transition duration-200">
                    Ajukan Cuti
                </button>
                <a href="<?= url('/karyawan/leave') ?>" 
                   class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-3 rounded text-center transition duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto calculate total days
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');
const totalDaysSpan = document.getElementById('totalDays');

function calculateDays() {
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            totalDaysSpan.textContent = diffDays + ' hari';
        } else {
            totalDaysSpan.textContent = '0 hari';
        }
    }
}

startDateInput.addEventListener('change', calculateDays);
endDateInput.addEventListener('change', calculateDays);

// Update min date for end_date when start_date changes
startDateInput.addEventListener('change', function() {
    endDateInput.min = this.value;
});

// Validasi file size
document.getElementById('attachment').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file && file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5MB');
        this.value = '';
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-2xl mx-auto py-8 px-4">

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

    <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
        <h1 class="text-3xl font-bold mb-6 text-heading">Ajukan Cuti</h1>

        <form action="<?= url('/karyawan/leave/store') ?>" method="POST" enctype="multipart/form-data" id="leaveForm">
            <!-- Jenis Cuti -->
            <div class="mb-4">
                <label for="leave_type" class="block text-sm font-medium text-heading mb-2">
                    Jenis Cuti <span class="text-red-500">*</span>
                </label>
                <select name="leave_type" id="leave_type" required
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow">
                    <option value="">-- Pilih Jenis Cuti --</option>
                    <option value="annual">Cuti Tahunan</option>
                    <option value="sick">Cuti Sakit</option>
                    <option value="emergency">Cuti Darurat</option>
                    <option value="unpaid">Cuti Tidak Dibayar</option>
                </select>
            </div>

            <!-- Tanggal Mulai -->
            <div class="mb-4">
                <label for="start_date" class="block text-sm font-medium text-heading mb-2">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" required
                    min="<?= date('Y-m-d') ?>"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow">
            </div>

            <!-- Tanggal Selesai -->
            <div class="mb-4">
                <label for="end_date" class="block text-sm font-medium text-heading mb-2">
                    Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" required
                    min="<?= date('Y-m-d') ?>"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow">
            </div>

            <!-- Total Hari (auto calculate) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-heading mb-2">Total Hari</label>
                <div class="bg-neutral-secondary-medium border border-default-medium p-3 rounded-base">
                    <span id="totalDays" class="text-lg font-semibold text-brand">0 hari</span>
                </div>
            </div>

            <!-- Alasan -->
            <div class="mb-4">
                <label for="reason" class="block text-sm font-medium text-heading mb-2">
                    Alasan Cuti <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="4" required
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body"
                    placeholder="Jelaskan alasan pengajuan cuti"></textarea>
            </div>

            <!-- Upload File -->
            <div class="mb-4">
                <label for="attachment" class="block text-sm font-medium text-heading mb-2">
                    Lampiran File (Opsional)
                </label>
                <div class="flex items-center justify-center w-full ">
                    <label for="attachment" class="flex flex-col items-center justify-center w-full h-64 bg-neutral-secondary-medium border border-dashed border-default-strong rounded-base cursor-pointer hover:bg-neutral-tertiary-medium">
                        <div class="flex flex-col items-center justify-center text-body pt-5 pb-6">
                            <i class="fa-solid fa-upload text-2xl w-8 h-8 mb-4"></i>
                            <p class="mb-2 text-sm"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs">PDF, JPG, PNG (MAX. 5MB)</p>
                        </div>
                        <input type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                    </label>
                </div>
                <p class="text-sm text-body mt-2">
                    Untuk cuti sakit, lampirkan surat keterangan dokter.
                </p>
            </div>


            <!-- Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    class="flex items-center justify-center flex-1 gap-2 text-white bg-brand hover:bg-brand-strong border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-5 py-2.5">
                    <i class="fa-solid fa-paper-plane text-base"></i>
                    Ajukan Cuti
                </button>
                <a
                    href="<?= url('/karyawan/leave') ?>"
                    class="flex items-center justify-center flex-1 text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-2.5">
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
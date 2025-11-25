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

            <!-- Tanggal Range -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-heading mb-2 ">
                    Pilih Rentang Tanggal
                </label>
                <div id="date-range-picker" date-rangepicker class="flex items-center gap-4 w-full">

                    <!-- start date -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                            </svg>
                        </div>
                        <input id="start_date" name="start_date" type="text"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                            placeholder="Select date start">
                    </div>

                    <span class="text-body">to</span>

                    <!-- end date -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                            </svg>
                        </div>
                        <input id="end_date" name="end_date" type="text"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                            placeholder="Select date end">
                    </div>
                </div>

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
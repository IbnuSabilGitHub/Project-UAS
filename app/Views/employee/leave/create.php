<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="p-4 sm:ml-64 mt-14">
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
                <div id="date-range-picker" date-rangepicker datepicker-format="yyyy-mm-dd" class="flex items-center gap-4 w-full">
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
                <input type="hidden" name="total_days" id="totalDaysInput" value="0">
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
                <label class="block mb-2.5 text-sm font-medium text-heading" for="attachment">Lampiran File</label>
                <input class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body" type="file" name="attachment" id="attachment" accept=".pdf,.jpg,.jpeg,.png">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PDF, JPG, PNG (MAX. 10MB)</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    class="flex items-center justify-center flex-1 gap-2 text-white bg-brand hover:bg-brand-strong border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-5 py-2.5">
                    <i class="fa-solid fa-paper-plane text-base"></i>
                    Ajukan Cuti
                </button>
                <button
                    type="button"
                    onclick="handleCancelLeaveForm(event)"
                    class="flex items-center justify-center flex-1 text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-full text-sm px-4 py-2.5">
                    Batal
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function handleCancelLeaveForm(event) {
        event.preventDefault();
        
        // Cek apakah form sudah diisi
        const leaveType = document.getElementById('leave_type').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const reason = document.getElementById('reason').value;
        const attachment = document.getElementById('attachment').files.length;
        
        const hasData = leaveType || startDate || endDate || reason.trim() !== '' || attachment > 0;
        
        if (hasData) {
            ToastManager.showAction({
                type: 'confirm',
                title: 'Konfirmasi Batal',
                message: 'Data pengajuan cuti yang sudah diisi akan hilang. Apakah Anda yakin ingin membatalkan?',
                confirmText: 'Ya, Batalkan',
                cancelText: 'Tidak',
                onConfirm: () => {
                    window.location.href = '<?= url('/karyawan/leave') ?>';
                }
            });
        } else {
            window.location.href = '<?= url('/karyawan/leave') ?>';
        }
    }

    // Element references
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const totalDaysSpan = document.getElementById('totalDays');
    const totalDaysInput = document.getElementById('totalDaysInput');
    const attachmentInput = document.getElementById('attachment');
    const leaveForm = document.getElementById('leaveForm');

    // Helper Function
    const setTotalDays = (days) => {
        totalDaysSpan.textContent = `${days} hari`;
        totalDaysInput.value = days;
    };

    // Hitung total hari
    function calculateDays() {
        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (!startDateInput.value || !endDateInput.value) {
            return setTotalDays(0);
        }

        if (isNaN(start) || isNaN(end) || end < start) {
            return setTotalDays(0);
        }

        const diffDays = Math.floor((end - start) / 86400000) + 1; // 86400000 = 1000 * 60 * 60 * 24
        setTotalDays(diffDays);
    }

    // Listener tanggal
    ['change', 'input', 'changeDate'].forEach(evt => {
        startDateInput.addEventListener(evt, calculateDays);
        endDateInput.addEventListener(evt, calculateDays);
    });

    // Update min end date
    startDateInput.addEventListener('change', () => {
        endDateInput.min = startDateInput.value;
    });

    // Validasi File
    attachmentInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedExtensions = /\.(pdf|jpg|jpeg|png)$/i;

        if (file.size > maxSize) {
            ToastManager.error('Ukuran file maksimal 10MB');
            return attachmentInput.value = '';
        }

        if (!allowedExtensions.test(file.name)) {
            ToastManager.error('Format file tidak valid.');
            return attachmentInput.value = '';
        }
    });

    // Validasi sebelum submit
    leaveForm.addEventListener('submit', (e) => {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (!startDate || !endDate) {
            e.preventDefault();
            return ToastManager.error('Silakan pilih tanggal mulai dan tanggal selesai');
        }

        // Pastikan hitungan terbaru
        calculateDays();

        if (parseInt(totalDaysInput.value) <= 0) {
            e.preventDefault();
            return ToastManager.error(`Total hari cuti harus lebih dari 0. Total: ${totalDaysInput.value}`);
        }
    });

    // Init saat load
    window.addEventListener('load', () => {
        if (startDateInput.value && endDateInput.value) {
            calculateDays();
        }
    });
</script>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
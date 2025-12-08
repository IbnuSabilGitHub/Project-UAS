<div class="p-4 sm:ml-64 mt-14">
    <h1 class="text-2xl font-bold mb-4 text-heading"><?= $title ?></h1>

    <div class="max-w-4xl  bg-neutral-primary-soft shadow-xs rounded-base p-6 border border-default">
        <?php
        $k = $karyawan ?? null;
        $isEdit = !empty($k);
        ?>

        <form action="<?= $isEdit ? url('/admin/karyawan/update') : url('/admin/karyawan/store') ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($k['id']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- NIK -->
                <div class="mb-3">
                    <label for="nik" class="block mb-2.5 text-sm font-medium text-heading">NIK</label>

                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="fa-solid fa-address-card text-body"></i>
                        </div>

                        <input
                            type="text"
                            id="nik"
                            name="nik"
                            required
                            value="<?= htmlspecialchars($k['nik'] ?? '') ?>"
                            placeholder="Masukkan NIK (16 digit)"
                            pattern="[0-9]{16}"
                            minlength="16"
                            maxlength="16"
                            title="NIK harus tepat 16 digit angka"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                    </div>
                </div>

                <!-- Nama -->
                <div class="mb-3">
                    <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Nama</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="fa-solid fa-user text-body"></i>
                        </div>
                        <input
                            name="name"
                            id="name"
                            required
                            value="<?= htmlspecialchars($k['name'] ?? '') ?>"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                            placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Your email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="fa-solid fa-at text-body"></i>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="<?= htmlspecialchars($k['email'] ?? '') ?>"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                            placeholder=" user@gmai.com">
                    </div>
                </div>

                <!-- No Telpon -->
                <div class="mb-3">
                    <label for="phone" class="block mb-2.5 text-sm font-medium text-heading">Telepon:</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                            </svg>
                        </div>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?= htmlspecialchars($k['phone'] ?? '') ?>"
                            required
                            pattern="[0-9]{10,13}"
                            title="Masukkan 10-13 digit nomor telepon"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                            placeholder="081234567890"
                            maxlength="13"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>

                <!-- Posisi -->
                <div class="mb-3">
                    <label for="position" class="block mb-2.5 text-sm font-medium text-heading">Posisi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <i class="fa-solid fa-briefcase text-body"></i>
                        </div>
                        <select
                            name="position"
                            id="position"
                            required
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium  text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs">
                            <option class="text-body" value="" disabled <?= empty($k['position']) ? 'selected' : '' ?>>Pilih posisi</option>
                            <?php foreach ($availablePositions as $pos): ?>
                                <option class="text-header" value="<?= htmlspecialchars($pos) ?>" <?= (isset($k['position']) && $k['position'] === $pos) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pos) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <!-- Tanggak Masuk -->
                <div class="mb-3">
                    <label for="join_date" class="block mb-2.5 text-sm font-medium text-heading">Tanggal Masuk</label>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                            </svg>
                        </div>
                        <input
                            name="join_date"
                            id="join_date"
                            required
                            value="<?= htmlspecialchars($k['join_date'] ?? '') ?>"
                            datepicker-format="yyyy-mm-dd"
                            datepicker
                            datepicker-autohide
                            datepicker-max-date="<?= date('Y-m-d') ?>"
                            type="text"
                            max="<?= date('Y-m-d') ?>"
                            class="block w-full ps-9 pe-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand px-3 py-2.5 shadow-xsplaceholder:text-body"
                            placeholder="Select date">
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="block mb-2.5 text-sm font-medium text-heading">Status</label>
                    <div class="flex flex-row gap-4 items-center justify-between">
                        <!-- Active -->
                        <div class="flex items-center ps-4 border border-default bg-neutral-secondary-medium rounded-base w-full">
                            <input
                                id="status-active"
                                type="radio"
                                name="status"
                                value="active"
                                <?= (!isset($k['status']) || $k['status'] === 'active') ? 'checked' : '' ?>
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="status-active" class="w-full py-4 select-none ms-2 text-sm font-medium text-heading">
                                Active
                            </label>
                        </div>

                        <!-- Inactive -->
                        <div class="flex items-center ps-4 border border-default bg-neutral-secondary-medium rounded-base w-full">
                            <input
                                id="status-inactive"
                                type="radio"
                                name="status"
                                value="inactive"
                                <?= (isset($k['status']) && $k['status'] === 'inactive') ? 'checked' : '' ?>
                                class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="status-inactive" class="w-full py-4 select-none ms-2 text-sm font-medium text-heading">
                                Inactive
                            </label>
                        </div>
                    </div>
                </div>

                <?php if (!$isEdit): ?>
                    <div class="mb-3">
                        <label class="block mb-2.5 text-sm font-medium text-heading">Buat Akun Sekarang?</label>
                        <div class="flex items-center">
                            <label for="create_account" class="flex items-center h-5">
                                <input type="checkbox" name="create_account" id="create_account" value="1"
                                    class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft">
                                <p class="ms-2 text-sm font-medium text-heading select-none">Buat akun & generate temp password</p>
                            </label>
                        </div>
                        <div class="text-xs text-body mt-2">Jika dicentang, sistem akan membuat akun dengan password sementara acak dan wajib diganti saat login pertama.</div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-8 flex items-center">
                <button type="submit"
                    class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Simpan
                </button>

                <button type="button" onclick="handleCancelForm(event)"
                    class="ml-3 text-body text-sm font-medium hover:text-heading transition duration-150 ease-in-out">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleCancelForm(event) {
        event.preventDefault();
        
        // Cek apakah form sudah diisi
        const form = document.querySelector('form');
        const formData = new FormData(form);
        let hasData = false;
        
        for (let [key, value] of formData.entries()) {
            if (key !== 'id' && key !== 'status' && value && value.trim() !== '') {
                hasData = true;
                break;
            }
        }
        
        if (hasData) {
            ToastManager.showAction({
                type: 'confirm',
                title: 'Konfirmasi Batal',
                message: 'Data yang sudah diisi akan hilang. Apakah Anda yakin ingin membatalkan?',
                confirmText: 'Ya, Batalkan',
                cancelText: 'Tidak',
                onConfirm: () => {
                    window.location.href = '<?= url('/admin/karyawan') ?>';
                }
            });
        } else {
            window.location.href = '<?= url('/admin/karyawan') ?>';
        }
    }
</script>

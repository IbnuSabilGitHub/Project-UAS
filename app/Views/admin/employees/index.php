<div class="p-4 sm:ml-64 mt-14">

    <!-- Statistik Karyawan -->
    <?php if (isset($statistics) && !empty($statistics)): ?>
        <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
            <h2 class="text-xl font-semibold mb-4 text-heading">Statistik Karyawan</h2>

            <!-- Card -->
            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <!-- Total Karyawan -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['total'] ?? 0 ?></p>
                    <p class="text-body">Total Karyawan</p>
                </div>

                <!-- Active -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['active'] ?? 0 ?></p>
                    <p class="text-body">Aktif</p>
                </div>

                <!-- Resigned -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $statistics['resigned'] ?? 0 ?></p>
                    <p class="text-body">Resign</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="overflow-y-auto overflow-x-auto shadow-md sm:rounded-base bg-neutral-secondary-soft p-4 space-y-4">
        <div class="flex flex-row items-center justify-between w-full">
            <h1 class="text-2xl font-bold text-heading">List Karyawan </h1>

            <!-- Wrapper filter -->
            <div class="flex flex-row space-x-4">
                <!-- Filter Status Employment -->
                <div>
                    <button id="dropdownHelperCheckboxButton"
                        data-dropdown-toggle="dropdownHelperCheckbox"
                        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
                        type="button">
                        Employment
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownHelperCheckbox"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownHelperCheckboxButton">

                            <!-- Active -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-checkbox-1" type="checkbox" value="active" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('active', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="helper-checkbox-1" class="ms-2 text-sm select-none font-medium text-heading">
                                        Aktif
                                    </label>
                                </div>
                            </li>

                            <!-- Resigned -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="helper-checkbox-3" type="checkbox" value="resigned" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('resigned', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="helper-checkbox-3" class="ms-2 text-sm select-none font-medium text-heading">
                                        Resign
                                    </label>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- Filter Posisi -->
                <div>
                    <button id="dropdownPositionFilterButton"
                        data-dropdown-toggle="dropdownPositionFilter"
                        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
                        type="button">
                        Posisi
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownPositionFilter"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-64">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownPositionFilterButton">
                            <?php foreach ($availablePositions as $index => $position): ?>
                                <li>
                                    <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                        <div class="flex items-center h-5">
                                            <input id="position-checkbox-<?= $index ?>" type="checkbox" value="<?= htmlspecialchars($position) ?>" name="position-filter"
                                                class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                                <?= (empty($currentPosition) || in_array($position, $currentPosition)) ? 'checked' : '' ?>>
                                        </div>
                                        <label for="position-checkbox-<?= $index ?>" class="ms-2 text-sm select-none font-medium text-heading">
                                            <?= htmlspecialchars($position) ?>
                                        </label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Search berdasarkan nama/NIK karyawan -->
                <form class="min-w-xs flex space-x-2" id="searchForm" method="GET">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-user text-sm text-body"></i>
                        </div>
                        <input type="text" name="search" id="simple-search" value="<?= htmlspecialchars($currentSearch ?? '') ?>" class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base ps-9 text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Cari nama/NIK karyawan..." />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center shrink-0 text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs rounded-base w-10 h-10 focus:outline-none">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Icon description</span>
                    </button>
                </form>

                <a href="<?= url('/admin/karyawan/create') ?>"
                    class="inline-flex items-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Tambah Karyawan
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto shadow-xs rounded-base border border-default" style="max-height: 650px;">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">NO</th>
                        <th scope="col" class="px-6 py-3 font-medium">NIK</th>
                        <th scope="col" class="px-6 py-3 font-medium">Nama</th>
                        <th scope="col" class="px-6 py-3 font-medium">Email</th>
                        <th scope="col" class="px-6 py-3 font-medium">Posisi</th>
                        <th scope="col" class="px-6 py-3 font-medium">Status</th>
                        <th scope="col" class="px-6 py-3 font-medium">Employment</th>
                        <th scope="col" class="px-6 py-3 font-medium">Akun</th>
                        <th scope="col" class="px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($karyawans as $index => $k): ?>
                        <tr class="bg-neutral-primary border-b border-default">

                            <!-- No -->
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($index + 1) ?>
                            </td>

                            <!-- NIK -->
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['nik']) ?>
                            </td>

                            <!-- Name -->
                            <th class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                <?= htmlspecialchars($k['name']) ?>
                            </th>

                            <!-- Email -->
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['email']) ?>
                            </td>

                            <!-- Position -->
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['position']) ?>
                            </td>

                            <!-- Karyawan status -->
                            <td class="px-6 py-4">
                                <?php if ($k['status'] === 'active'): ?>
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-success me-2"></div> Active
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-danger me-2"></div> Inactive
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Employment Status -->
                            <td class="px-6 py-4">
                                <?php
                                $emp = $k['employment_status'] ?? 'active';
                                $badge_class = 'bg-success-soft text-success-strong';
                                if ($emp === 'resigned' || $emp === 'terminated') {
                                    $badge_class = 'bg-danger-soft text-danger-strong';
                                } elseif ($emp === 'on_leave') {
                                    $badge_class = 'bg-warning-soft text-warning-strong';
                                }
                                ?>
                                <span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full <?= $badge_class ?>">
                                    <?= htmlspecialchars(ucwords($emp)) ?>
                                </span>
                            </td>

                            <!-- Account Status -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($k['user_id']) && $k['employment_status'] === 'active'): ?>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-success-soft text-success-strong">
                                            Aktif
                                        </span>
                                        <?php if (!empty($k['must_change_password'])): ?>
                                            <span class="flex items-center justify-center bg-danger-soft border border-danger-subtle text-fg-danger-strong text-xs font-medium h-5 w-5 rounded-full">
                                                <i class="fa-solid fa-unlock text-danger-strong text-xs fa-xs"></i>
                                                <span class="sr-only">Wajib ganti password</span>
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif ($k['employment_status'] === 'resigned'): ?>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-danger-soft text-danger-strong">
                                            Nonaktif
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-neutral-secondary-soft text-body">
                                            Belum Ada
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="px-6 py-4 text-center">
                                <button id="dropdownButton<?= $k['id'] ?>"
                                    data-dropdown-toggle="dropdown<?= $k['id'] ?>"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-heading bg-neutral-primary hover:bg-neutral-secondary-medium rounded-base focus:ring-4 focus:outline-none focus:ring-neutral-tertiary"
                                    type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                        <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="dropdown<?= $k['id'] ?>" class="z-10 hidden bg-neutral-primary border border-default-medium rounded-base shadow-lg w-48">
                                    <ul class="py-2 text-sm text-body" aria-labelledby="dropdownButton<?= $k['id'] ?>">
                                        <!-- Edit -->
                                        <li>
                                            <a href="<?= url('/admin/karyawan/edit') ?>?id=<?= $k['id'] ?>"
                                                class="flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-heading">
                                                <i class="fa-solid fa-pen-to-square w-4"></i>
                                                <span>Edit Karyawan</span>
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="my-1 border-default-medium">
                                        </li>

                                        <?php if (!empty($k['user_id'])): ?>
                                            <!-- Nonaktifkan Akun (hanya jika akun ada dan aktif) -->
                                            <?php if (($k['employment_status'] ?? 'active') === 'active'): ?>
                                                <li>
                                                    <form action="<?= url('/admin/karyawan/deactivate') ?>"
                                                        method="post"
                                                        class="block"
                                                        id="form-deactivate-<?= $k['id'] ?>"
                                                        onsubmit="return handleDeactivateAccount(event, '<?= $k['id'] ?>', '<?= htmlspecialchars($k['name'], ENT_QUOTES) ?>');">
                                                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-warning-strong font-medium">
                                                            <i class="fa-solid fa-user-slash w-4"></i>
                                                            <span>Nonaktifkan Akun</span>
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <!-- Aktifkan Akun (jika belum ada akun) -->
                                            <li>
                                                <form action="<?= url('/admin/karyawan/activate') ?>"
                                                    method="post"
                                                    class="block"
                                                    id="form-activate-<?= $k['id'] ?>"
                                                    onsubmit="return handleActivateAccount(event, '<?= $k['id'] ?>', '<?= htmlspecialchars($k['name'], ENT_QUOTES) ?>');">
                                                    <input type="hidden" name="karyawan_id" value="<?= $k['id'] ?>">
                                                    <button type="submit"
                                                        class="w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-success-strong font-medium">
                                                        <i class="fa-solid fa-user-check w-4"></i>
                                                        <span>Aktifkan Akun</span>
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                                            <li>
                                                <hr class="my-1 border-default-medium">
                                            </li>
                                            <!-- Delete (Super Admin Only) -->
                                            <li>
                                                <form action="<?= url('/admin/karyawan/delete') ?>"
                                                    method="post"
                                                    class="block"
                                                    id="form-delete-<?= $k['id'] ?>"
                                                    onsubmit="return handleDeleteEmployee(event, '<?= $k['id'] ?>', '<?= htmlspecialchars($k['name'], ENT_QUOTES) ?>');">
                                                    <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-danger-strong">
                                                        <i class="fa-solid fa-trash w-4"></i>
                                                        <span>Hapus Permanen</span>
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="w-full max-w-[18rem]">
        <div class="relative">
            <label for="npm-install-copy-text" class="sr-only">Label</label>
            <input id="npm-install-copy-text" type="text" class="col-span-6 bg-neutral-secondary-medium border border-default-medium text-body text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" value="npm install flowbite" disabled readonly>
            <button data-copy-to-clipboard-target="npm-install-copy-text" class="absolute flex items-center end-1.5 top-1/2 -translate-y-1/2 text-body bg-neutral-primary-strong border border-default-strong hover:bg-neutral-secondary-strong/70 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft font-medium leading-5 rounded text-xs px-3 py-1.5 focus:outline-none">
                <span id="default-message">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z" />
                        </svg>
                        <span class="text-xs font-semibold">Copy</span>
                    </span>
                </span>
                <span id="success-message" class="hidden">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 text-fg-brand me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                        </svg>
                        <span class="text-xs font-semibold text-fg-brand">Copied</span>
                    </span>
                </span>
            </button>
        </div>
    </div>

</div>

<!-- Include Modal Temporary Password -->
<?php require_once __DIR__ . '/../../layouts/components/modal-temp-password.php'; ?>

<!-- Include Toast Component -->
<?php require_once __DIR__ . '/../../layouts/components/toast.php'; ?>

<!-- Toast JavaScript -->
<script src="<?= asset('js/toast.js') ?>"></script>

<script>
    // JavaScript untuk menangani filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const statusCheckboxes = document.querySelectorAll('input[name="status-filter"]');
        const positionCheckboxes = document.querySelectorAll('input[name="position-filter"]');

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

            // Get checked positions
            const checkedPositions = Array.from(positionCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (checkedPositions.length > 0 && checkedPositions.length < positionCheckboxes.length) {
                checkedPositions.forEach(position => {
                    url.searchParams.append('position[]', position);
                });
            }

            // Redirect dengan filter
            window.location.href = url.toString();
        }

        // deteksi perubahan pada checkbox status
        statusCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });

        // deteksi perubahan pada checkbox position
        positionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
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

        // Tampilkan modal temporary password jika ada data dari session
        <?php if (isset($_SESSION['temp_password_data'])): ?>
            showTempPasswordModal(
                '<?= addslashes($_SESSION['temp_password_data']['email'] ?? '') ?>',
                '<?= addslashes($_SESSION['temp_password_data']['password'] ?? '') ?>'
            );
        <?php
            // Hapus data dari session setelah ditampilkan
            unset($_SESSION['temp_password_data']);
        endif;
        ?>
    });

    // Fungsi untuk menampilkan modal temporary password
    function showTempPasswordModal(email, password) {
        const modal = document.getElementById('temp-password-modal');
        const emailInput = document.getElementById('temp-email');
        const passwordInput = document.getElementById('temp-password');
        const closeBtn = document.getElementById('close-temp-password-modal');
        const confirmBtn = document.getElementById('confirm-close-modal');

        // Set nilai ke input fields
        if (emailInput) emailInput.value = email;
        if (passwordInput) passwordInput.value = password;

        // Tampilkan modal
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Event listener untuk close modal
        const closeModal = function() {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (confirmBtn) confirmBtn.addEventListener('click', closeModal);

        // Close modal jika klik di luar modal content
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }
    }

    // TOAST ACTION HANDLERS

    /**
     * Handle deactivate account with toast confirmation
     */
    function handleDeactivateAccount(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'update',
            title: 'Nonaktifkan Akun Karyawan',
            message: `Apakah Anda yakin ingin menonaktifkan akun untuk karyawan "${employeeName}"? Karyawan tidak akan bisa login setelah akun dinonaktifkan.`,
            confirmText: 'Ya, Nonaktifkan',
            cancelText: 'Batal',
            onConfirm: function() {
                // Submit form
                document.getElementById(`form-deactivate-${employeeId}`).submit();
            }
        });

        return false;
    }

    /**
     * Handle activate account with toast confirmation
     */
    function handleActivateAccount(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.confirm(
            `Aktifkan akun untuk karyawan "${employeeName}"? Sistem akan membuat kredensial login dan mengirimkan password temporary.`,
            function() {
                // Submit form
                document.getElementById(`form-activate-${employeeId}`).submit();
            }, {
                title: 'Aktifkan Akun Karyawan',
                confirmText: 'Ya, Aktifkan',
                cancelText: 'Batal'
            }
        );

        return false;
    }

    /**
     * Handle delete employee with toast confirmation
     */
    function handleDeleteEmployee(event, employeeId, employeeName) {
        event.preventDefault();

        ToastManager.showAction({
            type: 'delete',
            title: 'Hapus Karyawan Permanen',
            message: `Apakah Anda yakin ingin menghapus karyawan "${employeeName}" secara permanen? Data yang sudah dihapus tidak dapat dikembalikan!`,
            confirmText: 'Ya, Hapus Permanen',
            cancelText: 'Batal',
            onConfirm: function() {
                // Show loading
                ToastManager.info('Menghapus data karyawan...');

                // Submit form
                document.getElementById(`form-delete-${employeeId}`).submit();
            },
            onCancel: function() {
                ToastManager.info('Penghapusan dibatalkan');
            }
        });

        return false;
    }
</script>
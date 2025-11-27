<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">


    <?php if (isset($_SESSION['success'])): ?>
        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-base bg-green-50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div id="alert-danger" class="flex items-center p-4 mb-4 text-red-800 rounded-base bg-red-50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
    <?php endif; ?>


    <div class="overflow-y-auto overflow-x-auto shadow-md sm:rounded-base bg-neutral-secondary-soft p-4 space-y-4">
        <div class="flex flex-row items-center justify-between w-full">
            <h1 class="text-2xl font-bold text-heading">List Karyawan</h1>
            <a href="<?= url('/admin/karyawan/create') ?>"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                Tambah Karyawan
            </a>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">#</th>
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
                    <?php foreach ($karyawans as $k): ?>
                        <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['id']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['nik']) ?>
                            </td>
                            <th class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                <?= htmlspecialchars($k['name']) ?>
                            </th>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['email']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['position']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= htmlspecialchars($k['status']) ?>
                            </td>

                            <!-- Karyawan status -->
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
                                    <?php if (!empty($k['user_id'])): ?>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-success-soft text-success-strong">
                                            Aktif
                                        </span>
                                        <?php if (!empty($k['must_change_password'])): ?>
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-warning-soft text-warning-strong">Wajib Ganti</span>
                                        <?php endif; ?>
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
                                                        onsubmit="return confirm('Nonaktifkan akun karyawan ini?');">
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
                                                    onsubmit="return confirm('Aktifkan akun untuk karyawan ini?');">
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
                                                    onsubmit="return confirm('Hapus permanen karyawan ini? Data tidak dapat dikembalikan!');">
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
    <div class="mt-8">
        <a href="<?= url('/admin/dashboard') ?>"
            class="inline-block text-brand hover:text-brand-strong font-medium">
            <i class="fa-solid fa-arrow-left-long mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
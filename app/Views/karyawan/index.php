<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    
    <?php if (isset($_SESSION['success'])): ?>
        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-base bg-green-50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
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
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-heading">List Karyawan</h1>
        
        <a href="<?= url('/admin/karyawan/create') ?>" 
           class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Tambah Karyawan
        </a>
    </div>
    
    <div class="relative overflow-x-auto shadow-md sm:rounded-base">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">NIK</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Posisi</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Employment</th>
                    <th scope="col" class="px-6 py-3">Akun</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($karyawans as $k): ?>
                    <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors duration-150">
                        <td class="px-6 py-4"><?= htmlspecialchars($k['id']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($k['nik']) ?></td>
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            <?= htmlspecialchars($k['name']) ?>
                        </th>
                        <td class="px-6 py-4"><?= htmlspecialchars($k['email']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($k['position']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($k['status']) ?></td>

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

                        <td class="px-6 py-4">
                            <?php if (!empty($k['user_id'])): ?>
                                <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-full bg-success-soft text-success-strong">
                                    Aktif
                                </span>

                                <?php if (!empty($k['must_change_password'])): ?>
                                    <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-full bg-warning-soft text-warning-strong ml-2">Wajib Ganti</span>
                                <?php endif; ?>

                                <?php if (($k['employment_status'] ?? 'active') === 'active'): ?>
                                    <form action="<?= url('/admin/karyawan/deactivate') ?>" 
                                            method="post" 
                                            class="inline ml-2"
                                            onsubmit="return confirm('Nonaktifkan akun karyawan ini?');">
                                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                        <button type="submit" class="text-sm text-warning-strong hover:underline hover:text-warning font-medium">
                                            Nonaktifkan Akun
                                        </button>
                                    </form>
                                <?php endif; ?>

                            <?php else: ?>
                                <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-full bg-neutral-secondary-soft text-body">
                                    Belum Ada
                                </span>
                                <form action="<?= url('/admin/karyawan/activate') ?>" method="post" class="inline">
                                    <input type="hidden" name="karyawan_id" value="<?= $k['id'] ?>">
                                    <button type="submit"
                                                class="ml-2 text-brand text-sm hover:underline hover:text-brand-strong font-medium"
                                                onclick="return confirm('Aktifkan akun untuk karyawan ini?');">
                                        Aktifkan Akun
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="<?= url('/admin/karyawan/edit') ?>?id=<?= $k['id'] ?>"
                               class="font-medium text-fg-brand hover:underline mr-4">
                                Edit
                            </a>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                                <form action="<?= url('/admin/karyawan/delete') ?>" 
                                        method="post" 
                                        class="inline"
                                        onsubmit="return confirm('Hapus permanen karyawan ini?');">
                                    <input type="hidden" name="id" value="<?= $k['id'] ?>">
                                    <button type="submit" class="text-danger-strong hover:underline hover:text-danger font-medium">
                                        Hapus Permanen
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        <a href="<?= url('/admin/dashboard') ?>" 
           class="inline-block text-brand hover:text-brand-strong font-medium">
            ← Kembali ke Dashboard
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
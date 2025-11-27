<?php require_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
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
                <div class="mb-3">
                    <label for="nik" class="block mb-2.5 text-sm font-medium text-heading">NIK</label>
                    <input name="nik" id="nik" value="<?= htmlspecialchars($k['nik'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body" 
                           required>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="block mb-2.5 text-sm font-medium text-heading">Nama</label>
                    <input name="name" id="name" value="<?= htmlspecialchars($k['name'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body" 
                           required>
                </div>

                <div class="mb-3">
                    <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Email</label>
                    <input name="email" id="email" type="email" value="<?= htmlspecialchars($k['email'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="block mb-2.5 text-sm font-medium text-heading">Telepon</label>
                    <input name="phone" id="phone" value="<?= htmlspecialchars($k['phone'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                </div>

                <div class="mb-3">
                    <label for="position" class="block mb-2.5 text-sm font-medium text-heading">Posisi</label>
                    <input name="position" id="position" value="<?= htmlspecialchars($k['position'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                </div>
                
                <div class="mb-3">
                    <label for="join_date" class="block mb-2.5 text-sm font-medium text-heading">Tanggal Masuk</label>
                    <input name="join_date" id="join_date" type="date" value="<?= htmlspecialchars($k['join_date'] ?? '') ?>" 
                           class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow placeholder:text-body">
                </div>

                <div class="mb-3">
                    <label for="status" class="block mb-2.5 text-sm font-medium text-heading">Status</label>
                    <select name="status" id="status" 
                            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow">
                        <option value="active" <?= (isset($k['status']) && $k['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($k['status']) && $k['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
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
                
                <a href="<?= url('/admin/karyawan') ?>" 
                   class="ml-3 text-body text-sm font-medium hover:text-heading transition duration-150 ease-in-out">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
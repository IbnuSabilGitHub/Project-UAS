<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="flex items-center justify-center min-h-screen w-full">
    <div class="w-full max-w-md bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
        <h1 class="text-2xl font-semibold text-heading mb-6">Ganti Password</h1>
        
        
        <form method="post" action="<?= url('/change-password') ?>" class="space-y-6">
            <div class="gap-2">
                <label class="block mb-2.5 text-sm font-medium text-heading">Password Baru</label>
                <input type="password" name="new_password" 
                       class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" 
                       placeholder="Masukkan password baru" 
                       required minlength="8">
            </div>
            <div class="gap-2">
                <label class="block mb-2.5 text-sm font-medium text-heading">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" 
                       class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" 
                       placeholder="Konfirmasi password baru" 
                       required minlength="8">
            </div>
            <button type="submit" 
                    class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none w-full">
                Simpan Password
            </button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
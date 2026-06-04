<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="flex items-center justify-center min-h-screen w-full">
    <div class="w-full max-w-md bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
        <h1 class="text-2xl font-semibold text-heading mb-6">Ganti Password</h1>
        
        <form method="post" action="<?= url('/change-password') ?>" class="space-y-6" novalidate>
            <div class="gap-2">
                <label class="block mb-2.5 text-sm font-medium text-heading">Password Baru</label>
                <input type="password" name="new_password" 
                       class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" 
                       placeholder="Masukkan password baru" 
                       required>
                <p id="new_password_error" class="hidden text-xs text-danger-strong mt-1.5 flex items-center">
                    <i class="fa-solid fa-circle-exclamation me-1.5 shrink-0"></i>
                    <span class="error-text"></span>
                </p>
            </div>
            <div class="gap-2">
                <label class="block mb-2.5 text-sm font-medium text-heading">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" 
                       class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" 
                       placeholder="Konfirmasi password baru" 
                       required>
                <p id="confirm_password_error" class="hidden text-xs text-danger-strong mt-1.5 flex items-center">
                    <i class="fa-solid fa-circle-exclamation me-1.5 shrink-0"></i>
                    <span class="error-text"></span>
                </p>
            </div>
            <button type="submit" 
                    class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none w-full">
                Simpan Password
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const newPasswordInput = document.getElementsByName('new_password')[0];
    const confirmPasswordInput = document.getElementsByName('confirm_password')[0];
    
    const newPasswordError = document.getElementById('new_password_error');
    const confirmPasswordError = document.getElementById('confirm_password_error');

    function validateNewPassword() {
        const val = newPasswordInput.value;
        if (val.length === 0) {
            showError(newPasswordInput, newPasswordError, 'Password baru wajib diisi.');
            return false;
        } else if (val.length < 8) {
            showError(newPasswordInput, newPasswordError, `Password baru minimal harus 8 karakter (saat ini ${val.length} karakter).`);
            return false;
        } else {
            clearError(newPasswordInput, newPasswordError);
            return true;
        }
    }

    function validateConfirmPassword() {
        const val = confirmPasswordInput.value;
        const newVal = newPasswordInput.value;
        if (val.length === 0) {
            showError(confirmPasswordInput, confirmPasswordError, 'Konfirmasi password wajib diisi.');
            return false;
        } else if (val !== newVal) {
            showError(confirmPasswordInput, confirmPasswordError, 'Konfirmasi password tidak cocok dengan password baru.');
            return false;
        } else {
            clearError(confirmPasswordInput, confirmPasswordError);
            return true;
        }
    }

    function showError(input, errorEl, message) {
        errorEl.querySelector('.error-text').textContent = message;
        errorEl.classList.remove('hidden');
        input.classList.remove('border-default-medium', 'focus:ring-brand', 'focus:border-brand');
        input.classList.add('border-danger-strong', 'focus:ring-danger-strong', 'focus:border-danger-strong');
    }

    function clearError(input, errorEl) {
        errorEl.classList.add('hidden');
        input.classList.remove('border-danger-strong', 'focus:ring-danger-strong', 'focus:border-danger-strong');
        input.classList.add('border-default-medium', 'focus:ring-brand', 'focus:border-brand');
    }

    newPasswordInput.addEventListener('input', function() {
        if (newPasswordInput.value.length > 0) {
            validateNewPassword();
        } else {
            clearError(newPasswordInput, newPasswordError);
        }
        
        if (confirmPasswordInput.value.length > 0) {
            validateConfirmPassword();
        }
    });

    confirmPasswordInput.addEventListener('input', function() {
        if (confirmPasswordInput.value.length > 0) {
            validateConfirmPassword();
        } else {
            clearError(confirmPasswordInput, confirmPasswordError);
        }
    });

    form.addEventListener('submit', function(e) {
        const isNewValid = validateNewPassword();
        const isConfirmValid = validateConfirmPassword();

        if (!isNewValid || !isConfirmValid) {
            e.preventDefault();
        }
    });
});
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
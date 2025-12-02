<div class="flex items-center justify-center min-h-screen w-full">
    <div class="w-full max-w-sm bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-base mb-4">
                <?= htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-base mb-4">
                <?= htmlspecialchars($success ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('/login') ?>" class="space-y-6">
            <h5 class="text-xl font-semibold text-heading mb-6">Sign in to our platform</h5>

            <div class="gap-2">
                <label for="email" class="block mb-2.5 text-sm font-medium text-heading">Your email</label>
                <input type="email"
                    id="email"
                    name="email"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="Enter your email"
                    required />
            </div>

            <div class="gap-2">
                <label for="password" class="block mb-2.5 text-sm font-medium text-heading">Your password</label>
                <input type="password"
                    id="password"
                    name="password"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="•••••••••"
                    required />
            </div>


            <button type="submit"
                class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none w-full mb-3">
                Login to your account
            </button>


        </form>
    </div>
</div>
<div class="flex items-center justify-center min-h-screen w-full">
    <div class="w-full max-w-sm bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
        <form method="POST" action="<?= url('/karyawan/login') ?>" class="space-y-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-soft rounded-full mb-4">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                </div>
                <h5 class="text-xl font-semibold text-heading">Login Karyawan</h5>
                <p class="text-sm text-body mt-2">Masuk dengan akun karyawan</p>
            </div>

            <div class="gap-2">
                <label for="username" class="block mb-2.5 text-sm font-medium text-heading">Username</label>
                <input type="text"
                    id="username"
                    name="username"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="Masukkan username"
                    required />
            </div>

            <div class="gap-2">
                <label for="password" class="block mb-2.5 text-sm font-medium text-heading">Password</label>
                <input type="password"
                    id="password"
                    name="password"
                    class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="•••••••••"
                    required />
            </div>

            <div class="flex flex-row items-center justify-between gap-4">
                <button type="submit"
                    class="w-full text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Login
                </button>
                <a href="<?= url('/') ?>" class="w-fulltext-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
<form method="POST" action="<?= url('/karyawan/login') ?>">
    <div>
        <label for="username">Username</label>
        <input type="text"
            id="username"
            name="username"
            placeholder="Masukkan username"
            required />
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password"
            id="password"
            name="password"
            placeholder="•••••••••"
            required />
    </div>

    <button type="submit">
        Login sebagai Karyawan
    </button>

    <div>
        <a href="<?= url('/') ?>">
            ← Kembali ke halaman utama
        </a>
    </div>
</form>
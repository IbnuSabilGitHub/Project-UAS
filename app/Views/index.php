<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HRIS - Pilih Login</title>
  <link rel="stylesheet" href="<?= url('/assets/css/output.css') ?>">
  <script src="<?= url('/assets/js/apply-theme.js') ?>"></script>
</head>

<body class="bg-neutral-secondary-soft">
  <div class="flex items-center justify-center min-h-screen w-full p-4">
    <div class="w-full max-w-4xl">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-heading mb-3">Sistem HRIS</h1>
        <p class="text-body text-lg">Selamat datang di Human Resource Information System</p>
      </div>

      <!-- Login Options Cards -->
      <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Admin Login Card -->
        <a href="<?= url('/admin/login') ?>"
          class="group block bg-neutral-primary-soft p-8 border-2 border-default rounded-base shadow-xs hover:shadow-md transition-all duration-300 hover:border-brand transform hover:-translate-y-1">
          <div class="text-center">
            <!-- Icon -->
            <div class="mb-6 flex justify-center">
              <div class="bg-brand-soft p-6 rounded-full group-hover:bg-brand group-hover:scale-110 transition-all duration-300">
                <svg class="w-12 h-12 text-brand group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                  </path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-heading mb-3 group-hover:text-brand transition-colors duration-300">
              Login sebagai Admin
            </h2>

            <!-- Description -->
            <p class="text-body mb-6">
              Akses penuh untuk mengelola karyawan, absensi, dan persetujuan cuti
            </p>

            <!-- Button -->
            <div class="inline-flex items-center text-brand font-semibold group-hover:translate-x-2 transition-transform duration-300">
              Masuk sebagai Admin
              <i class="fa-solid fa-arrow-up-right-from-square text-sm ml-2"></i>
            </div>
          </div>
        </a>

        <!-- Karyawan Login Card -->
        <a href="<?= url('/karyawan/login') ?>"
          class="group block bg-neutral-primary-soft p-8 border-2 border-default rounded-base shadow-xs hover:shadow-md transition-all duration-300 hover:border-brand transform hover:-translate-y-1">
          <div class="text-center">
            <!-- Icon -->
            <div class="mb-6 flex justify-center">
              <div class="bg-brand-soft p-6 rounded-full group-hover:bg-brand group-hover:scale-110 transition-all duration-300">
                <svg class="w-12 h-12 text-brand group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                  </path>
                </svg>
              </div>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-heading mb-3 group-hover:text-brand transition-colors duration-300">
              Login sebagai Karyawan
            </h2>

            <!-- Description -->
            <p class="text-body mb-6">
              Akses untuk absensi harian, pengajuan cuti, dan melihat riwayat kehadiran
            </p>

            <!-- Button -->
            <div class="inline-flex items-center text-brand font-semibold group-hover:translate-x-2 transition-transform duration-300">
              Masuk sebagai Karyawan

              <i class="fa-solid fa-arrow-up-right-from-square text-sm ml-2"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <script src="<?= url('/assets/js/theme.js') ?>"></script>
</body>

</html>
<div class="p-4 sm:ml-64 mt-14 bg-neutral-primary">
  <!-- Success Alert -->
  <?php if (!empty($success)): ?>
    <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
      <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
      </svg>
      <span class="sr-only">Info</span>
      <div class="ms-3 text-sm font-medium"><?= htmlspecialchars($success) ?></div>
    </div>
  <?php endif; ?>

  <!-- Main Dashboard Container with Border -->
  <div class="bg-neutral-primary-soft border-2 border-default rounded-lg shadow-lg overflow-hidden">
    
    <!-- Header with Sidebar Color -->
    <div class="bg-gradient-to-r from-neutral-primary via-neutral-primary-soft to-neutral-primary-soft px-6 py-5 border-b-2 border-default shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
          <!-- User-provided icon (IconScout style) -->
          <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" id="grin-tongue-wink-alt" class="w-6 h-6" aria-hidden="true">
            <path fill="#6563FF" d="M9.21,10.54a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41,3.08,3.08,0,0,0-4.24,0,1,1,0,1,0,1.41,1.41A1,1,0,0,1,9.21,10.54ZM12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20ZM15,9a1,1,0,1,0,1,1A1,1,0,0,0,15,9Zm0,4H9a1,1,0,0,0,0,2,3,3,0,0,0,6,0,1,1,0,0,0,0-2Zm-3,3a1,1,0,0,1-1-1h2A1,1,0,0,1,12,16Z"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-heading">Dashboard Admin</h1>
        </div>
      </div>
    </div>

    <div class="p-6 space-y-6">
      
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- Total Karyawan Card -->
        <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-body text-sm font-medium mb-3">Total Karyawan</p>
              <p class="text-5xl font-bold text-heading"><?= $statsKaryawan['total'] ?? 0 ?></p>
            </div>
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="head-side" class="w-6 h-6" aria-hidden="true">
                <path fill="#6563FF" d="M13.23 2.003a7.372 7.372 0 0 0-5.453 2.114A7.44 7.44 0 0 0 5.5 9.5v.03l-1.904 4.044A1 1 0 0 0 4.5 15h1v2a2.002 2.002 0 0 0 2 2h1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0 0-2h-3v-3a1 1 0 0 0-1-1h-.424l1.34-2.844a.99.99 0 0 0 .095-.465L7.5 9.5a5.455 5.455 0 0 1 1.67-3.947 5.527 5.527 0 0 1 4-1.55 5.685 5.685 0 0 1 5.33 5.77l-1.967 7.504a1.01 1.01 0 0 0 .006.534l1 3.466A1.001 1.001 0 0 0 18.5 22a1.018 1.018 0 0 0 .277-.04 1 1 0 0 0 .684-1.237l-.924-3.2 1.93-7.267A1.031 1.031 0 0 0 20.5 10v-.228a7.698 7.698 0 0 0-7.27-7.769Z"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Akun Aktif Card -->
        <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-body text-sm font-medium mb-3">Akun Aktif</p>
              <p class="text-5xl font-bold text-heading"><?= $statsKaryawan['active'] ?? 0 ?></p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
          </div>
        </div>

        <!-- Bergabung Bulan Ini Card -->
        <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-body text-sm font-medium mb-3">Bergabung Bulan Ini</p>
              <p class="text-5xl font-bold text-heading"><?= $statsKaryawan['new_this_month'] ?? 0 ?></p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="user-plus" class="w-6 h-6" aria-hidden="true">
                <path fill=" #6563FF" d="M21,10.5H20v-1a1,1,0,0,0-2,0v1H17a1,1,0,0,0,0,2h1v1a1,1,0,0,0,2,0v-1h1a1,1,0,0,0,0-2Zm-7.7,1.72A4.92,4.92,0,0,0,15,8.5a5,5,0,0,0-10,0,4.92,4.92,0,0,0,1.7,3.72A8,8,0,0,0,2,19.5a1,1,0,0,0,2,0,6,6,0,0,1,12,0,1,1,0,0,0,2,0A8,8,0,0,0,13.3,12.22ZM10,11.5a3,3,0,1,1,3-3A3,3,0,0,1,10,11.5Z"></path>
              </svg>
            </div>
          </div>
        </div>

      </div>

      <!-- Karyawan Berdasarkan Status -->
      <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-heading mb-4 pb-3 border-b-2 border-default flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          Karyawan Berdasarkan Status
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-2">
          <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-5 flex items-center gap-4 hover:bg-white/50 transition-colors">
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
            </div>
            <div>
              <p class="text-xs text-body uppercase tracking-wide">Active</p>
              <p class="text-3xl font-bold text-heading"><?= $statsKaryawan['active'] ?? 0 ?></p>
            </div>
          </div>

          <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-5 flex items-center gap-4 hover:bg-white/50 transition-colors">
            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" id="meh-alt" class="w-5 h-5" aria-hidden="true">
                  <path fill="#ff9a63ff" d="M9,11h1a1,1,0,0,0,0-2H9a1,1,0,0,0,0,2Zm6,3H9a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2Zm0-5H14a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2ZM12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"></path>
                </svg>
              </div>
            <div>
              <p class="text-xs text-body uppercase tracking-wide">Inactive</p>
              <p class="text-3xl font-bold text-heading"><?= $statsKaryawan['inactive'] ?? 0 ?></p>
            </div>
          </div>

          <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-5 flex items-center gap-4 hover:bg-white/50 transition-colors">
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
              <p class="text-xs text-body uppercase tracking-wide">Resigned</p>
              <p class="text-3xl font-bold text-heading"><?= $statsKaryawan['resigned'] ?? 0 ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Karyawan Berdasarkan Posisi -->
      <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          Karyawan Berdasarkan Posisi
        </h2>
        <div class="space-y-3">
          <?php 
            $positions = $statsKaryawan['by_position'] ?? [];
            $total = array_sum($positions);
            $colorShades = ['from-blue-400 to-blue-600', 'from-purple-400 to-purple-600', 'from-pink-400 to-pink-600', 'from-green-400 to-green-600', 'from-yellow-400 to-yellow-600'];
            $colorIndex = 0;
            
            if (empty($positions)): 
          ?>
            <p class="text-body text-center py-4">Belum ada data posisi karyawan</p>
          <?php 
            else:
              foreach ($positions as $position => $count): 
                $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                $gradient = $colorShades[$colorIndex % count($colorShades)];
                $colorIndex++;
          ?>
            <div class="group">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-heading"><?= htmlspecialchars($position ?? '-') ?></span>
                <span class="text-xs font-bold text-body bg-white/50 px-2 py-1 rounded"><?= $count ?></span>
              </div>
              <div class="w-full bg-neutral-secondary rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r <?= $gradient ?> h-2 rounded-full transition-all duration-500 group-hover:shadow-lg" style="width: <?= number_format($percentage, 0) ?>%;"></div>
              </div>
              <p class="text-xs text-body mt-1"><?= number_format($percentage, 1) ?>% dari total</p>
            </div>
          <?php 
              endforeach;
            endif;
          ?>
        </div>
      </div>

      <!-- Statistics Cuti & Absensi -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Statistik Cuti -->
        <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
          <h3 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Statistik Cuti
          </h3>
          <div class="grid grid-cols-3 gap-3">
            <!-- Pending -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg group-hover:bg-orange-200 dark:group-hover:bg-orange-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Pending</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsLeave['pending'] ?? 0 ?></p>
            </div>

            <!-- Approved -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Approved</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsLeave['approved'] ?? 0 ?></p>
            </div>

            <!-- Rejected -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Rejected</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsLeave['rejected'] ?? 0 ?></p>
            </div>
          </div>
        </div>

        <!-- Statistik Absensi -->
        <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
          <h3 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Statistik Absensi
          </h3>
          <div class="grid grid-cols-3 gap-3">
            <!-- Tepat Waktu -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Tepat Waktu</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['on_time'] ?? 0 ?></p>
            </div>

            <!-- Terlambat -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg group-hover:bg-orange-200 dark:group-hover:bg-orange-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Terlambat</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['late'] ?? 0 ?></p>
            </div>

            <!-- Tidak Hadir -->
            <div class="bg-white/30 backdrop-blur-sm border border-default rounded-lg p-4 hover:bg-white/50 transition-colors group">
              <div class="flex items-center justify-center mb-3">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Tidak Hadir</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['absent'] ?? 0 ?></p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

  <script>
    // Alert auto-hide after 5 seconds
    setTimeout(() => {
      const alert = document.getElementById('alert-success');
      if (alert) {
        alert.style.display = 'none';
      }
    }, 5000);
  </script>
</div>

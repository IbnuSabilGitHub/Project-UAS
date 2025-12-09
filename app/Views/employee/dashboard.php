<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="p-4 sm:ml-64 mt-14 bg-neutral-primary">

  <!-- Main Dashboard Container -->
  <div class="bg-neutral-primary-soft border-2 border-default rounded-lg shadow-lg overflow-hidden">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-neutral-primary via-neutral-primary-soft to-neutral-primary-soft px-6 py-5 border-b-2 border-default shadow-sm">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="user-circle" class="w-6 h-6" aria-hidden="true">
            <path fill="#6563FF" d="M12,2A10,10,0,0,0,4.65,18.76h0a10,10,0,0,0,14.7,0h0A10,10,0,0,0,12,2Zm0,18a8,8,0,0,1-5.55-2.25,6,6,0,0,1,11.1,0A8,8,0,0,1,12,20ZM10,10a2,2,0,1,1,2,2A2,2,0,0,1,10,10Zm8.91,6A8,8,0,0,0,15,12.62a4,4,0,1,0-6,0A8,8,0,0,0,5.09,16,7.92,7.92,0,0,1,4,12a8,8,0,0,1,16,0A7.92,7.92,0,0,1,18.91,16Z"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-heading">Dashboard Karyawan</h1>
          <p class="text-sm text-body">Selamat datang, <?= htmlspecialchars($username ?? 'Karyawan') ?></p>
        </div>
      </div>
    </div>

    <div class="p-6 space-y-6">
      
      <!-- STATISTIK CUTI -->
      <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          Statistik Cuti
        </h2>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <!-- Total Cuti -->
          <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-5 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-body text-xs font-medium mb-2">Total Pengajuan</p>
                <p class="text-4xl font-bold text-heading"><?= $statsLeave['total'] ?? 0 ?></p>
              </div>
              <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Pending -->
          <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-5 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-body text-xs font-medium mb-2">Pending</p>
                <p class="text-4xl font-bold text-heading"><?= $statsLeave['pending'] ?? 0 ?></p>
              </div>
              <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Approved -->
          <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-5 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-body text-xs font-medium mb-2">Disetujui</p>
                <p class="text-4xl font-bold text-heading"><?= $statsLeave['approved'] ?? 0 ?></p>
              </div>
              <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Rejected -->
          <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-5 hover:shadow-lg hover:border-default-medium transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-body text-xs font-medium mb-2">Ditolak</p>
                <p class="text-4xl font-bold text-heading"><?= $statsLeave['rejected'] ?? 0 ?></p>
              </div>
              <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Cuti Berdasarkan Tipe -->
        <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-5">
          <h3 class="text-sm font-bold text-heading mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            Cuti Berdasarkan Tipe
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php 
              $leaveTypes = $statsLeave['by_type'] ?? [];
              $typeLabels = [
                'annual' => 'Annual Leave',
                'sick' => 'Sick Leave',
                'unpaid' => 'Unpaid Leave',
                'emergency' => 'Emergency Leave',
                'maternity' => 'Maternity Leave',
                'paternity' => 'Paternity Leave'
              ];
              $typeColors = [
                'annual' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-600'],
                'sick' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-600'],
                'unpaid' => ['bg' => 'bg-gray-100 dark:bg-gray-900/30', 'text' => 'text-gray-600'],
                'emergency' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-600'],
                'maternity' => ['bg' => 'bg-pink-100 dark:bg-pink-900/30', 'text' => 'text-pink-600'],
                'paternity' => ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600']
              ];

              if (empty($leaveTypes)): 
            ?>
              <div class="col-span-3 text-center py-4 text-body">
                Belum ada data pengajuan cuti
              </div>
            <?php else: 
              foreach ($leaveTypes as $type => $data):
                $label = $typeLabels[$type] ?? ucfirst($type);
                $colors = $typeColors[$type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
            ?>
              <div class="bg-neutral-primary border border-default rounded-lg p-4 flex items-center gap-3 hover:shadow-md transition-shadow">
                <div class="p-2 <?= $colors['bg'] ?> rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 <?= $colors['text'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-body font-medium"><?= htmlspecialchars($label) ?></p>
                  <p class="text-2xl font-bold text-heading"><?= $data['count'] ?? 0 ?></p>
                  <p class="text-xs text-body"><?= $data['total_days'] ?? 0 ?> hari</p>
                </div>
              </div>
            <?php 
              endforeach;
            endif; 
            ?>
          </div>
        </div>

        <!-- Total Hari Cuti -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
          <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-5">
            <div class="flex items-center gap-3">
              <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs text-body uppercase tracking-wide font-semibold">Total Hari Disetujui</p>
                <p class="text-3xl font-bold text-heading"><?= $statsLeave['total_days_approved'] ?? 0 ?> <span class="text-base font-normal text-body">hari</span></p>
              </div>
            </div>
          </div>

          <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-5">
            <div class="flex items-center gap-3">
              <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs text-body uppercase tracking-wide font-semibold">Cuti Tahun Ini</p>
                <p class="text-3xl font-bold text-heading"><?= $statsLeave['tahun_ini']['total'] ?? 0 ?> <span class="text-base font-normal text-body">pengajuan</span></p>
                <p class="text-xs text-body"><?= $statsLeave['tahun_ini']['total_days'] ?? 0 ?> hari total</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ==================== STATISTIK ABSENSI ==================== -->
      <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
          Statistik Absensi
        </h2>

        <!-- Absensi Bulan Ini -->
        <div class="mb-6">
          <h3 class="text-sm font-bold text-heading mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Bulan Ini
          </h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Kehadiran -->
            <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-4 transition-colors">
              <div class="flex items-center justify-center mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Total Hadir</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['bulan_ini']['total_attendance'] ?? 0 ?></p>
            </div>

            <!-- Tepat Waktu -->
            <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-4 transition-colors">
              <div class="flex items-center justify-center mb-2">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Tepat Waktu</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['bulan_ini']['on_time'] ?? 0 ?></p>
            </div>

            <!-- Terlambat -->
            <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-4 transition-colors">
              <div class="flex items-center justify-center mb-2">
                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Terlambat</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['bulan_ini']['late'] ?? 0 ?></p>
            </div>

            <!-- Belum Checkout -->
            <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-4 transition-colors">
              <div class="flex items-center justify-center mb-2">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                  </svg>
                </div>
              </div>
              <p class="text-xs text-body uppercase tracking-wide text-center font-semibold mb-1">Belum Checkout</p>
              <p class="text-2xl font-bold text-heading text-center"><?= $statsAttendance['bulan_ini']['not_checkout'] ?? 0 ?></p>
            </div>
          </div>
        </div>

        <!-- Absensi Keseluruhan -->
        <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-5">
          <h3 class="text-sm font-bold text-heading mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Statistik Keseluruhan
          </h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center">
              <p class="text-xs text-body uppercase tracking-wide font-semibold mb-1">Total</p>
              <p class="text-3xl font-bold text-heading"><?= $statsAttendance['keseluruhan']['total_attendance'] ?? 0 ?></p>
            </div>
            <div class="text-center">
              <p class="text-xs text-body uppercase tracking-wide font-semibold mb-1">Tepat Waktu</p>
              <p class="text-3xl font-bold text-green-600"><?= $statsAttendance['keseluruhan']['on_time'] ?? 0 ?></p>
            </div>
            <div class="text-center">
              <p class="text-xs text-body uppercase tracking-wide font-semibold mb-1">Terlambat</p>
              <p class="text-3xl font-bold text-orange-600"><?= $statsAttendance['keseluruhan']['late'] ?? 0 ?></p>
            </div>
            <div class="text-center">
              <p class="text-xs text-body uppercase tracking-wide font-semibold mb-1">Setengah Hari</p>
              <p class="text-3xl font-bold text-yellow-600"><?= $statsAttendance['keseluruhan']['half_day'] ?? 0 ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- ==================== RIWAYAT 7 HARI TERAKHIR ==================== -->
      <div class="bg-gradient-to-br from-neutral-primary-soft to-neutral-primary border-2 border-default rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
        <h2 class="text-lg font-bold text-heading mb-5 pb-3 border-b-2 border-default flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Riwayat Absensi 7 Hari Terakhir
        </h2>

        <?php 
          $recentAttendance = $statsAttendance['tujuh_hari_terakhir'] ?? [];
          if (empty($recentAttendance)): 
        ?>
          <div class="text-center py-8 text-body">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="font-medium">Belum ada riwayat absensi</p>
          </div>
        <?php else: ?>
          <div class="space-y-3">
            <?php 
              foreach ($recentAttendance as $attendance):
                $status = $attendance['status'] ?? 'present';
                $tanggal = $attendance['tanggal'] ?? '';
                $checkIn = $attendance['check_in'] ?? '';
                $checkOut = $attendance['check_out'] ?? null;

                // Status styling
                $statusConfig = [
                  'present' => [
                    'bg' => 'bg-green-100 dark:bg-green-900/30',
                    'text' => 'text-green-600',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'label' => 'Tepat Waktu'
                  ],
                  'late' => [
                    'bg' => 'bg-orange-100 dark:bg-orange-900/30',
                    'text' => 'text-orange-600',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'label' => 'Terlambat'
                  ],
                  'half_day' => [
                    'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                    'text' => 'text-yellow-600',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'label' => 'Setengah Hari'
                  ]
                ];

                $config = $statusConfig[$status] ?? $statusConfig['present'];

                // Format tanggal
                $dateObj = DateTime::createFromFormat('Y-m-d', $tanggal);
                $formattedDate = $dateObj ? $dateObj->format('d M Y') : $tanggal;

                // Format waktu
                $checkInTime = $checkIn ? date('H:i', strtotime($checkIn)) : '-';
                $checkOutTime = $checkOut ? date('H:i', strtotime($checkOut)) : 'Belum checkout';
            ?>
              <div class="bg-white/30 dark:bg-neutral-primary-soft backdrop-blur-sm border border-default rounded-lg p-4 transition-all hover:shadow-md">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-4 flex-1">
                    <!-- Status Icon -->
                    <div class="p-3 <?= $config['bg'] ?> rounded-lg <?= $config['text'] ?>">
                      <?= $config['icon'] ?>
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                      <div class="flex items-center gap-2 mb-1">
                        <p class="font-bold text-heading"><?= htmlspecialchars($formattedDate) ?></p>
                        <span class="px-2 py-1 text-xs font-semibold <?= $config['text'] ?> <?= $config['bg'] ?> rounded">
                          <?= $config['label'] ?>
                        </span>
                      </div>
                      <div class="flex items-center gap-4 text-sm text-body">
                        <div class="flex items-center gap-1">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                          </svg>
                          <span>Check-in: <strong><?= htmlspecialchars($checkInTime) ?></strong></span>
                        </div>
                        <div class="flex items-center gap-1">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                          </svg>
                          <span>Check-out: <strong><?= htmlspecialchars($checkOutTime) ?></strong></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
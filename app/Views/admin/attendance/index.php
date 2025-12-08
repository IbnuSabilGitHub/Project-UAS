<div class="p-4 sm:ml-64 mt-14">

    <?php if (!empty($success)): ?>
        <div class="flex items-center p-4 mb-4 text-green-800 rounded-base bg-green-50" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($success) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="flex items-center p-4 mb-4 text-red-800 rounded-base bg-red-50" role="alert">
            <svg class="shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Danger</span>
            <div class="ms-3 text-sm font-medium">
                <?= htmlspecialchars($error) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Statistik Absensi -->
    <?php if (isset($stats) && !empty($stats)): ?>
        <div class="bg-neutral-primary-soft shadow-xs rounded-base p-6 mb-6 border border-default">
            <h2 class="text-xl font-semibold mb-4 text-heading">Statistik Absensi</h2>

            <!-- Card -->
            <div class="grid md:grid-cols-5 gap-4 mb-6">
                <!-- Total Absensi -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $stats['total_attendance'] ?? 0 ?></p>
                    <p class="text-body">Total Absensi</p>
                </div>

                <!-- Tepat Waktu -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $stats['on_time'] ?? 0 ?></p>
                    <p class="text-body">Tepat Waktu</p>
                </div>

                <!-- Terlambat -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $stats['late'] ?? 0 ?></p>
                    <p class="text-body">Terlambat</p>
                </div>

                <!-- Half Day -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $stats['half_day'] ?? 0 ?></p>
                    <p class="text-body">Half Day</p>
                </div>

                <!-- Belum Checkout -->
                <div class="flex flex-col bg-neutral-primary p-6 rounded-base border border-default">
                    <p class="mb-2 text-2xl font-semibold tracking-tight text-heading"><?= $stats['not_checkout'] ?? 0 ?></p>
                    <p class="text-body">Belum Checkout</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="overflow-y-auto overflow-x-auto shadow-md sm:rounded-base bg-neutral-secondary-soft p-4 space-y-4">
        <div class="flex flex-row items-center justify-between w-full">
            <h1 class="text-2xl font-bold text-heading">Manajemen Absensi</h1>

            <!-- Wrapper filter -->
            <div class="flex flex-row space-x-4">
                <!-- Filter Tanggal (Last) -->
                <div>

                    <!-- Button dropdown periode -->
                    <button id="dropdownPeriodButton"
                        data-dropdown-toggle="dropdownPeriod"
                        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
                        type="button">
                        <span id="periodButtonText">
                            <?php
                            $periodText = match($currentPeriod ?? 'today') {
                                'today' => 'Hari Ini',
                                'week' => 'Minggu Terakhir',
                                'month' => 'Bulan Terakhir',
                                'all' => 'Semua Data',
                                default => 'Hari Ini'
                            };
                            echo $periodText;
                            ?>
                        </span>
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdownPeriod"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownPeriodButton">
                            <!-- Hari Ini -->
                            <li>
                                <button type="button" data-period="today"
                                    class="period-option w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-heading rounded <?= ($currentPeriod ?? 'today') === 'today' ? 'bg-brand-soft' : '' ?>">
                                    <i class="fa-solid fa-calendar-day w-4"></i>
                                    <span>Hari Ini</span>
                                </button>
                            </li>

                            <!-- Minggu Terakhir -->
                            <li>
                                <button type="button" data-period="week"
                                    class="period-option w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-heading rounded <?= ($currentPeriod ?? '') === 'week' ? 'bg-brand-soft' : '' ?>">
                                    <i class="fa-solid fa-calendar-week w-4"></i>
                                    <span>Minggu Terakhir</span>
                                </button>
                            </li>

                            <!-- Bulan Terakhir -->
                            <li>
                                <button type="button" data-period="month"
                                    class="period-option w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-heading rounded <?= ($currentPeriod ?? '') === 'month' ? 'bg-brand-soft' : '' ?>">
                                    <i class="fa-solid fa-calendar-days w-4"></i>
                                    <span>Bulan Terakhir</span>
                                </button>
                            </li>

                            <!-- Semua Data -->
                            <li>
                                <button type="button" data-period="all"
                                    class="period-option w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-neutral-tertiary-medium text-heading rounded <?= ($currentPeriod ?? '') === 'all' ? 'bg-brand-soft' : '' ?>">
                                    <i class="fa-solid fa-calendar w-4"></i>
                                    <span>Semua Data</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Status Attendance -->
                <div>
                    <button id="dropdownStatusButton"
                        data-dropdown-toggle="dropdownStatus"
                        class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
                        type="button">
                        Status Absensi
                        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownStatus"
                        class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-56">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdownStatusButton">

                            <!-- Present -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="status-present" type="checkbox" value="present" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('present', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="status-present" class="ms-2 text-sm select-none font-medium text-heading">
                                        Hadir
                                    </label>
                                </div>
                            </li>

                            <!-- Late -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="status-late" type="checkbox" value="late" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('late', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="status-late" class="ms-2 text-sm select-none font-medium text-heading">
                                        Terlambat
                                    </label>
                                </div>
                            </li>

                            <!-- Half Day -->
                            <li>
                                <div class="flex p-2 w-full hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    <div class="flex items-center h-5">
                                        <input id="status-half-day" type="checkbox" value="half_day" name="status-filter"
                                            class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                            <?= (empty($currentStatus) || in_array('half_day', $currentStatus)) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="status-half-day" class="ms-2 text-sm select-none font-medium text-heading">
                                        Half Day
                                    </label>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <!-- Search berdasarkan nama/NIK karyawan -->
                <form class="min-w-xs flex space-x-2" id="searchForm" method="GET">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-solid fa-user text-sm text-body"></i>
                        </div>
                        <input type="text" name="search" id="simple-search" value="<?= htmlspecialchars($currentSearch ?? '') ?>" class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base ps-9 text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Cari nama/NIK karyawan..." />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center shrink-0 text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs rounded-base w-10 h-10 focus:outline-none">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Icon description</span>
                    </button>
                </form>

                <a href="<?= url('/admin/attendance/export') ?>"
                    class="inline-flex items-center text-white bg-green-600 box-border border border-transparent hover:bg-green-700 focus:ring-4 focus:ring-green-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                    Export CSV
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto shadow-xs rounded-base border border-default" style="max-height: 650px;">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead class="text-xs text-heading uppercase bg-neutral-secondary-soft sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">Tanggal</th>
                        <th scope="col" class="px-6 py-3 font-medium">NIK</th>
                        <th scope="col" class="px-6 py-3 font-medium">Nama Karyawan</th>
                        <th scope="col" class="px-6 py-3 font-medium">Jabatan</th>
                        <th scope="col" class="px-6 py-3 font-medium">Check-in</th>
                        <th scope="col" class="px-6 py-3 font-medium">Check-out</th>
                        <th scope="col" class="px-6 py-3 font-medium">Durasi</th>
                        <th scope="col" class="px-6 py-3 font-medium">Status</th>
                        <th scope="col" class="px-6 py-3 font-medium">Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($attendances)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-neutral-tertiary-medium mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-body">Tidak ada data absensi</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($attendances as $record): ?>
                            <tr class="bg-neutral-primary border-b border-default">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('d/m/Y', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <?= htmlspecialchars($record['nik']) ?>
                                </td>
                                <th class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                    <?= htmlspecialchars($record['name']) ?>
                                </th>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-body">
                                    <?= htmlspecialchars($record['position']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?= date('H:i:s', strtotime($record['check_in'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($record['check_out']): ?>
                                        <?= date('H:i:s', strtotime($record['check_out'])) ?>
                                    <?php else: ?>
                                        <span class="text-neutral-tertiary-medium">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($record['check_out']): ?>
                                        <?php
                                        $diff = strtotime($record['check_out']) - strtotime($record['check_in']);
                                        $hours = floor($diff / 3600);
                                        $minutes = floor(($diff % 3600) / 60);
                                        echo "{$hours}j {$minutes}m";
                                        ?>
                                    <?php else: ?>
                                        <span class="text-neutral-tertiary-medium">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusBadge = match($record['status']) {
                                        'present' => '<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-success-soft text-success-strong">Hadir</span>',
                                        'late' => '<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-warning-soft text-warning-strong">Terlambat</span>',
                                        'half_day' => '<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-neutral-secondary-soft text-body">Half Day</span>',
                                        default => '<span class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-neutral-secondary-soft text-body">' . htmlspecialchars($record['status']) . '</span>'
                                    };
                                    echo $statusBadge;
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-body max-w-xs truncate" title="<?= htmlspecialchars($record['notes'] ?? '') ?>">
                                    <?= htmlspecialchars($record['notes'] ?: '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk menangani filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const statusCheckboxes = document.querySelectorAll('input[name="status-filter"]');
        const periodOptions = document.querySelectorAll('.period-option');

        // Fungsi untuk menerapkan filter
        function applyFilters(selectedPeriod = null) {
            const url = new URL(window.location.href);
            url.search = ''; // Clear existing params

            // Ambil nilai pencarian
            const searchValue = document.getElementById('simple-search').value.trim();
            if (searchValue) {
                url.searchParams.set('search', searchValue);
            }

            // Ambil nilai periode
            const periodValue = selectedPeriod || (new URLSearchParams(window.location.search).get('period') || 'today');
            if (periodValue && periodValue !== 'today') {
                url.searchParams.set('period', periodValue);
            }

            // Get checked statuses
            const checkedStatuses = Array.from(statusCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (checkedStatuses.length > 0 && checkedStatuses.length < 3) {
                checkedStatuses.forEach(status => {
                    url.searchParams.append('status[]', status);
                });
            }

            // Redirect dengan filter
            window.location.href = url.toString();
        }

        // deteksi perubahan pada checkbox status
        statusCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                applyFilters();
            });
        });

        // deteksi klik pada period options
        periodOptions.forEach(option => {
            option.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                applyFilters(period);
            });
        });

        // deteksi submit form pencarian
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                applyFilters();
            });
        }
    });
</script>
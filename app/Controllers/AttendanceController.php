<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Attendance.php';

/**
 * AttendanceController - Mengelola fitur absensi karyawan
 * 
 * Fitur untuk karyawan: check-in, check-out, view history
 * Fitur untuk admin: view semua absensi, filter, export CSV
 */
class AttendanceController extends BaseController {
    private $model;        

    public function __construct() {
        parent::__construct(); // Initialize userModel dari BaseController
        $this->model = new Attendance();
    }

    // KARYAWAN METHODS

    /**
     * Halaman absensi karyawan
     * 
     * @return void
     */
    public function index() {
        $this->ensureKaryawan();
        
        $karyawanId = $this->getKaryawanId();
        
        if (!$karyawanId) {
            setFlash('error', 'Data karyawan tidak ditemukan');
            redirect('/karyawan/dashboard');
        }

        // Cek status hari ini
        $todayStatus = $this->model->hasCheckedInToday($karyawanId);
        
        // Ambil riwayat 30 hari terakhir
        $history = $this->model->getHistory($karyawanId, 30);
        
        // Ambil statistik bulan ini
        $stats = $this->model->getMonthlyStats($karyawanId);

        $data = [
            'title' => 'Absensi Saya',
            'todayStatus' => $todayStatus,
            'history' => $history,
            'stats' => $stats,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ];

        $this->render('employee/attendance', $data);
    }

    /**
     * Proses check-in karyawan
     * 
     * @return void
     */
    public function checkIn() {
        $this->ensureKaryawan();
        $this->validateMethod('POST', '/karyawan/attendance');

        $karyawanId = $this->getKaryawanId();
        $notes = trim($_POST['notes'] ?? '');

        if ($this->model->checkIn($karyawanId, $notes)) {
            setFlash('success', 'Check-in berhasil!');
        } else {
            setFlash('error', 'Anda sudah check-in hari ini');
        }

        redirect('/karyawan/attendance');
    }

    /**
     * Proses check-out karyawan
     * 
     * @return void
     */
    public function checkOut() {
        $this->ensureKaryawan();
        $this->validateMethod('POST', '/karyawan/attendance');

        $karyawanId = $this->getKaryawanId();
        $notes = trim($_POST['notes'] ?? '');

        if ($this->model->checkOut($karyawanId, $notes)) {
            setFlash('success', 'Check-out berhasil!');
        } else {
            setFlash('error', 'Anda belum check-in atau sudah check-out hari ini');
        }

        redirect('/karyawan/attendance');
    }

    // ADMIN METHODS

    /**
     * Halaman manajemen absensi untuk admin
     * 
     * @return void
     */
    public function adminIndex() {
        $this->ensureAdmin();

        // Ambil parameter filter dari query string
        $period = $_GET['period'] ?? 'today';
        $searchName = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? [];
        
        // Normalize status filter menjadi array
        if (!is_array($statusFilter)) {
            $statusFilter = [$statusFilter];
        }

        // Convert period ke date range
        $dateRange = $this->convertPeriodToDateRange($period);

        // Ambil data absensi dengan filter
        $attendances = $this->model->getWithFilters(
            $dateRange['start'], 
            $dateRange['end'], 
            $searchName, 
            $statusFilter
        );

        // Statistik ringkas berdasarkan filter
        $stats = $this->model->getAdminStats($dateRange['start'], $dateRange['end']);

        $data = [
            'title' => 'Manajemen Absensi',
            'attendances' => $attendances,
            'stats' => $stats,
            'currentPeriod' => $period,
            'currentSearch' => $searchName,
            'currentStatus' => $statusFilter,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ];

        $this->render('admin/attendance/index', $data);
    }

    /**
     * Export data absensi ke CSV
     * 
     * @return void
     */
    public function export() {
        $this->ensureAdmin();

        // Ambil semua data absensi
        $attendances = $this->model->getAll(10000);

        // Set header untuk download CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=absensi_' . date('Y-m-d') . '.csv');

        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, ['NIK', 'Nama', 'Check-in', 'Check-out', 'Status', 'Catatan']);

        // Data rows
        foreach ($attendances as $attendance) {
            fputcsv($output, [
                $attendance['nik'] ?? '-',
                $attendance['karyawan_name'] ?? '-',
                $attendance['check_in'] ?? '-',
                $attendance['check_out'] ?? '-',
                $attendance['status'] ?? '-',
                $attendance['notes'] ?? '-'
            ]);
        }

        fclose($output);
        exit;
    }

    // HELPER METHODS

    /**
     * Konversi periode ke rentang tanggal
     * 
     * @param string $period Identifier periode (today, week, month, all)
     * @return array Associative array dengan keys 'start' dan 'end'
     */
    private function convertPeriodToDateRange($period) {
        $end = date('Y-m-d');
        $start = '';

        switch ($period) {
            case 'today':
                $start = date('Y-m-d');
                break;
            case 'week':
                $start = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $start = date('Y-m-d', strtotime('-30 days'));
                break;
            case 'all':
            default:
                $start = '';
                $end = '';
                break;
        }

        return ['start' => $start, 'end' => $end];
    }
}

<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Attendance.php';

class AttendanceController extends BaseController {
    private $model;        

    public function __construct() {
        $this->model = new Attendance();
    }


    /**
     * Halaman attendance karyawan
     */
    public function index() {
        $this->ensureKaryawan();
        
        // Ambil karyawan_id dari session
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $karyawanId = $result['karyawan_id'];

        if (!$karyawanId) {
            setFlash('error', 'Data karyawan tidak ditemukan');
            redirect('/karyawan/dashboard');
        }

        // Cek status hari ini
        $todayStatus = $this->model->hasCheckedInToday($karyawanId);
        // Ambil riwayat
        $history = $this->model->getHistory($karyawanId, 30);
        // Ambil statistik bulan ini
        $stats = $this->model->getMonthlyStats($karyawanId);

        $data = [
            'title' => 'Absensi Saya',
            'todayStatus' => $todayStatus,
            'history' => $history,
            'stats' => $stats,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];

        unset($_SESSION['success'], $_SESSION['error']);
        $this->render('employee/attendance', $data);
    }

    /**
     * Proses check-in
     */
    public function checkIn() {
        $this->ensureKaryawan();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/karyawan/attendance');
        }

        // Ambil karyawan_id
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $karyawanId = $result['karyawan_id'];

        $notes = trim($_POST['notes'] ?? '');

        if ($this->model->checkIn($karyawanId, $notes)) {
            setFlash('success', 'Check-in berhasil!');
        } else {
            setFlash('error', 'Anda sudah check-in hari ini');
        }

        redirect('/karyawan/attendance');
    }

    /**
     * Proses check-out
     */
    public function checkOut() {
        $this->ensureKaryawan();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/karyawan/attendance');
        }

        // Ambil karyawan_id
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $karyawanId = $result['karyawan_id'];

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
     * Halaman attendance untuk admin (melihat semua data)
     */
    public function adminIndex() {
        $this->ensureAdmin();

        // Ambil parameter filter
        $period = $_GET['period'] ?? 'today';
        $searchName = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? [];
        
        // Jika status dikirim sebagai array kosong atau tidak ada, tampilkan semua
        if (!is_array($statusFilter)) {
            $statusFilter = [$statusFilter];
        }

        // Convert period ke date range
        $dateRange = $this->convertPeriodToDateRange($period);

        // Ambil data absensi dengan filter (tanpa pagination)
        $attendances = $this->model->getWithFilters($dateRange['start'], $dateRange['end'], $searchName, $statusFilter);

        // Statistik ringkas
        $stats = $this->model->getAdminStats($dateRange['start'], $dateRange['end']);

        $data = [
            'title' => 'Manajemen Absensi',
            'attendances' => $attendances,
            'stats' => $stats,
            'currentPeriod' => $period,
            'currentSearch' => $searchName,
            'currentStatus' => $statusFilter,
            'email' => $_SESSION['email'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];

        unset($_SESSION['success'], $_SESSION['error']);
        $this->render('admin/attendance/index', $data);
    }

    /**
     * Convert period string ke date range
     * 
     * @param string $period
     * @return array ['start' => string, 'end' => string]
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

    /**
     * Export data absensi ke CSV
     * Export semua data tanpa filter
     */
    public function export() {
        $this->ensureAdmin();

        // Ambil semua data tanpa filter
        $attendances = $this->model->getAll(10000);

        // Set header untuk download CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=absensi_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, ['Tanggal', 'NIK', 'Nama Karyawan', 'Check-in', 'Check-out', 'Durasi (Jam)', 'Status', 'Catatan']);

        // Data
        foreach ($attendances as $record) {
            $duration = '';
            if ($record['check_out']) {
                $diff = strtotime($record['check_out']) - strtotime($record['check_in']);
                $hours = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);
                $duration = "{$hours}:{$minutes}";
            }

            $statusLabel = match($record['status']) {
                'present' => 'Hadir',
                'late' => 'Terlambat',
                'half_day' => 'Half Day',
                default => $record['status']
            };

            fputcsv($output, [
                date('d/m/Y', strtotime($record['check_in'])),
                $record['nik'],
                $record['name'],
                date('H:i:s', strtotime($record['check_in'])),
                $record['check_out'] ? date('H:i:s', strtotime($record['check_out'])) : '-',
                $duration,
                $statusLabel,
                $record['notes'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }
}

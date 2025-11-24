<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Attendance.php';

class AttendanceController {
    private $model;

    public function __construct() {
        $this->model = new Attendance();
    }

    private function render($view, $data = []) {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }

    private function ensureKaryawan() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');
        }
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
            $_SESSION['error'] = 'Data karyawan tidak ditemukan';
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
        $this->render('attendance/index', $data);
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
            $_SESSION['success'] = 'Check-in berhasil!';
        } else {
            $_SESSION['error'] = 'Anda sudah check-in hari ini';
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
            $_SESSION['success'] = 'Check-out berhasil!';
        } else {
            $_SESSION['error'] = 'Anda belum check-in atau sudah check-out hari ini';
        }

        redirect('/karyawan/attendance');
    }

    // ===== ADMIN METHODS =====

    private function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');
        }
    }

    /**
     * Halaman attendance untuk admin (melihat semua data)
     */
    public function adminIndex() {
        $this->ensureAdmin();

        // Ambil parameter filter
        $date = $_GET['date'] ?? '';
        $searchName = $_GET['search_name'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Ambil data absensi dengan filter
        $attendances = $this->model->getAllWithFilter($date, $searchName, $status, $limit, $offset);
        
        // Hitung total untuk pagination
        $total = $this->model->countAllWithFilter($date, $searchName, $status);
        $totalPages = ceil($total / $limit);

        // Statistik ringkas
        $stats = $this->model->getAdminStats($date);

        $data = [
            'title' => 'Manajemen Absensi',
            'attendances' => $attendances,
            'stats' => $stats,
            'filters' => [
                'date' => $date,
                'search_name' => $searchName,
                'status' => $status
            ],
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'totalRecords' => $total
            ],
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];

        unset($_SESSION['success'], $_SESSION['error']);
        $this->render('attendance/admin', $data);
    }

    /**
     * Export data absensi ke CSV
     */
    public function export() {
        $this->ensureAdmin();

        $date = $_GET['date'] ?? '';
        $searchName = $_GET['search_name'] ?? '';
        $status = $_GET['status'] ?? '';

        // Ambil semua data tanpa limit
        $attendances = $this->model->getAllWithFilter($date, $searchName, $status, 10000, 0);

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

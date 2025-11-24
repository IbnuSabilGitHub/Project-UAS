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
}

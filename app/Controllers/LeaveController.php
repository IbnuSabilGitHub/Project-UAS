<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/LeaveRequest.php';

class LeaveController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new LeaveRequest();
    }


    private function getKaryawanId() {
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['karyawan_id'] ?? null;
    }

    /**
     * Halaman riwayat pengajuan cuti karyawan
     */
    public function index() {
        $this->ensureKaryawan();
        
        $karyawanId = $this->getKaryawanId();
        if (!$karyawanId) {
            $_SESSION['error'] = 'Data karyawan tidak ditemukan';
            redirect('/karyawan/dashboard');
        }

        $leaves = $this->model->getByKaryawan($karyawanId);
        $totalDaysUsed = $this->model->getTotalDaysThisYear($karyawanId);

        $data = [
            'title' => 'Riwayat Cuti Saya',
            'leaves' => $leaves,
            'totalDaysUsed' => $totalDaysUsed,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];

        unset($_SESSION['success'], $_SESSION['error']);
        $this->render('employee/leave/index', $data);
    }

    /**
     * Halaman form pengajuan cuti
     */
    public function create() {
        $this->ensureKaryawan();

        $data = [
            'title' => 'Ajukan Cuti',
            'error' => $_SESSION['error'] ?? null
        ];

        unset($_SESSION['error']);
        $this->render('employee/leave/create', $data);
    }

    /**
     * Konversi format tanggal dari MM/DD/YYYY ke YYYY-MM-DD
     */
    private function convertDateFormat($date) {
        if (empty($date)) return null;
        
        // Jika sudah format YYYY-MM-DD, return langsung
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        // Konversi dari MM/DD/YYYY ke YYYY-MM-DD
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
            $month = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return "$year-$month-$day";
        }
        
        // Coba parse dengan DateTime sebagai fallback
        try {
            $dt = new DateTime($date);
            return $dt->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Proses submit pengajuan cuti
     */
    public function store() {
        $this->ensureKaryawan();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/karyawan/leave');
        }

        $karyawanId = $this->getKaryawanId();
        if (!$karyawanId) {
            $_SESSION['error'] = 'Data karyawan tidak ditemukan';
            redirect('/karyawan/dashboard');
        }

        // Validasi input
        $leaveType = trim($_POST['leave_type'] ?? '');
        $startDateRaw = trim($_POST['start_date'] ?? '');
        $endDateRaw = trim($_POST['end_date'] ?? '');
        $reason = trim($_POST['reason'] ?? '');

        // Konversi format tanggal
        $startDate = $this->convertDateFormat($startDateRaw);
        $endDate = $this->convertDateFormat($endDateRaw);

        if (empty($leaveType) || empty($startDate) || empty($endDate) || empty($reason)) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            redirect('/karyawan/leave/create');
        }

        // Validasi tanggal
        if ($startDate > $endDate) {
            $_SESSION['error'] = 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai';
            redirect('/karyawan/leave/create');
        }

        // Hitung total hari
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $totalDays = $interval->days + 1;

        // Cek overlap
        if ($this->model->hasOverlap($karyawanId, $startDate, $endDate)) {
            $_SESSION['error'] = 'Tanggal yang dipilih bentrok dengan pengajuan cuti lainnya';
            redirect('/karyawan/leave/create');
        }

        // Validasi file upload jika ada
        $file = null;
        $maxSize = 10 * 1024 * 1024; // 10MB 
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = 'Error saat upload file';
                redirect('/karyawan/leave/create');
            }
            
            // Validasi ukuran file (max 10MB)
            if ($_FILES['attachment']['size'] > $maxSize) {
                $_SESSION['error'] = 'Ukuran file maksimal 5MB';
                redirect('/karyawan/leave/create');
            }
            
            $file = $_FILES['attachment'];
        }

        $data = [
            'karyawan_id' => $karyawanId,
            'leave_type' => $leaveType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $reason
        ];

        $result = $this->model->create($data, $file);

        if ($result['success']) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil dikirim';
            redirect('/karyawan/leave');
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Gagal mengajukan cuti';
            redirect('/karyawan/leave/create');
        }
    }

    /**
     * Hapus pengajuan cuti (hanya yang pending)
     */
    public function delete() {
        $this->ensureKaryawan();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/karyawan/leave');
        }

        $id = (int)($_POST['id'] ?? 0);
        $karyawanId = $this->getKaryawanId();

        // Pastikan cuti ini milik karyawan yang login dan statusnya pending
        $leave = $this->model->find($id);
        
        if (!$leave || $leave['karyawan_id'] != $karyawanId) {
            $_SESSION['error'] = 'Pengajuan cuti tidak ditemukan';
            redirect('/karyawan/leave');
        }

        if ($leave['status'] !== 'pending') {
            $_SESSION['error'] = 'Hanya pengajuan cuti pending yang bisa dihapus';
            redirect('/karyawan/leave');
        }

        if ($this->model->delete($id)) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengajuan cuti';
        }

        redirect('/karyawan/leave');
    }
}

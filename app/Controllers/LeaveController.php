<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/LeaveRequest.php';

/**
 * LeaveController - Mengelola pengajuan cuti karyawan
 * 
 * Fitur untuk karyawan: create leave request, view history,
 * delete pending request
 */
class LeaveController extends BaseController {
    private $model;

    public function __construct() {
        parent::__construct(); // Initialize userModel
        $this->model = new LeaveRequest();
    }

    /**
     * Halaman riwayat pengajuan cuti karyawan
     * 
     * @return void
     */
    public function index() {
        $this->ensureKaryawan();
        
        // Gunakan helper method dari BaseController (MVC compliant)
        $karyawanId = $this->getKaryawanId();
        
        if (!$karyawanId) {
            setFlash('error', 'Data karyawan tidak ditemukan');
            redirect('/karyawan/dashboard');
        }

        $leaves = $this->model->getByKaryawan($karyawanId);
        $totalApproved = $this->model->getTotalApprovedThisYear($karyawanId);
        $totalRejected = $this->model->getTotalRejectedThisYear($karyawanId);

        $data = [
            'title' => 'Riwayat Cuti Saya',
            'leaves' => $leaves,
            'totalApproved' => $totalApproved,
            'totalRejected' => $totalRejected,
            'success' => $this->getFlash('success'),
            'error' => $this->getFlash('error')
        ];

        $this->render('employee/leave/index', $data);
    }

    /**
     * Halaman form pengajuan cuti
     * 
     * @return void
     */
    public function create() {
        $this->ensureKaryawan();

        $data = [
            'title' => 'Ajukan Cuti',
            'error' => $this->getFlash('error')
        ];

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
     * 
     * @return void
     */
    public function store() {
        $this->ensureKaryawan();
        $this->validateMethod('POST', '/karyawan/leave');

        // Gunakan helper method dari BaseController
        $karyawanId = $this->getKaryawanId();
        
        if (!$karyawanId) {
            setFlash('error', 'Data karyawan tidak ditemukan');
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
            setFlash('error', 'Semua field wajib diisi');
            redirect('/karyawan/leave/create');
        }

        // Validasi tanggal: tidak boleh di masa lalu
        $today = date('Y-m-d');
        if ($startDate < $today) {
            setFlash('error', 'Tanggal mulai cuti tidak boleh di masa lalu');
            redirect('/karyawan/leave/create');
        }
        
        if ($endDate < $today) {
            setFlash('error', 'Tanggal selesai cuti tidak boleh di masa lalu');
            redirect('/karyawan/leave/create');
        }
        
        // Validasi: end date harus >= start date
        if ($startDate > $endDate) {
            setFlash('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
            redirect('/karyawan/leave/create');
        }

        // Hitung total hari
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $totalDays = $interval->days + 1;

        // Cek overlap
        if ($this->model->hasOverlap($karyawanId, $startDate, $endDate)) {
            setFlash('error', 'Tanggal yang dipilih bentrok dengan pengajuan cuti lainnya');
            redirect('/karyawan/leave/create');
        }

        // Validasi file upload jika ada
        $file = null;
        $maxSize = 10 * 1024 * 1024; // 10MB 
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
                setFlash('error', 'Error saat upload file');
                redirect('/karyawan/leave/create');
            }
            
            // Validasi ukuran file (max 10MB)
            if ($_FILES['attachment']['size'] > $maxSize) {
                setFlash('error', 'Ukuran file maksimal 10MB');
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
            setFlash('success', 'Pengajuan cuti berhasil dikirim');
            redirect('/karyawan/leave');
        } else {
            setFlash('error', $result['message'] ?? 'Gagal mengajukan cuti');
            redirect('/karyawan/leave/create');
        }
    }

    /**
     * Hapus pengajuan cuti (hanya yang pending)
     * 
     * @return void
     */
    public function delete() {
        $this->ensureKaryawan();
        $this->validateMethod('POST', '/karyawan/leave');

        $id = (int)($_POST['id'] ?? 0);
        $karyawanId = $this->getKaryawanId();

        // Pastikan cuti ini milik karyawan yang login dan statusnya pending
        $leave = $this->model->find($id);
        
        if (!$leave || $leave['karyawan_id'] != $karyawanId) {
            setFlash('error', 'Pengajuan cuti tidak ditemukan');
            redirect('/karyawan/leave');
        }

        if ($leave['status'] !== 'pending') {
            setFlash('error', 'Hanya pengajuan cuti pending yang bisa dihapus');
            redirect('/karyawan/leave');
        }

        if ($this->model->delete($id)) {
            setFlash('success', 'Pengajuan cuti berhasil dihapus');
        } else {
            setFlash('error', 'Gagal menghapus pengajuan cuti');
        }

        redirect('/karyawan/leave');
    }
}

<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/PengajuanCuti.php';
require_once __DIR__ . '/../Models/Karyawan.php';

class CutiController {
    private $model;
    private $karyawanModel;

    public function __construct() {
        $this->model = new PengajuanCuti();
        $this->karyawanModel = new Karyawan();
    }

    /**
     * Render view dengan data
     * 
     * @param string $view
     * @param array $data
     */
    private function render($view, $data = []) {
        extract($data);
        require __DIR__ . "/../Views/{$view}.php";
    }

    /**
     * Pastikan user adalah admin
     */
    private function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');
        }
    }

    /**
     * Upload file PDF/Image dokumen pendukung
     * Kompatibel dengan implementasi karyawan (leave_attachments)
     * 
     * @param array $file File dari $_FILES
     * @return string|false Filename jika berhasil, false jika gagal
     */
    private function uploadDocument($file) {
        // Validasi file
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // File tidak wajib
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Error saat upload file';
            return false;
        }

        // Validasi tipe file (PDF, JPG, PNG - kompatibel dengan fitur karyawan)
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['error'] = 'Hanya file PDF, JPG, dan PNG yang diperbolehkan';
            return false;
        }

        // Validasi ukuran file (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Ukuran file maksimal 5MB';
            return false;
        }

        // Generate nama file unik
        $uploadDir = __DIR__ . '/../../public/uploads/leave_attachments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'leave_' . uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $fileName; // Return filename only (kompatibel dengan LeaveRequest model)
        }

        $_SESSION['error'] = 'Gagal mengupload file';
        return false;
    }

    /**
     * Hapus file dokumen dari storage
     * 
     * @param string $fileName
     * @return bool
     */
    private function deleteDocument($fileName) {
        if (empty($fileName)) {
            return true;
        }

        $fullPath = __DIR__ . '/../../storage/leave_attachments/' . $fileName;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return true;
    }

    /**
     * Menampilkan daftar pengajuan cuti dengan filter
     */
    public function index() {
        $this->ensureAdmin();
        
        // Ambil parameter filter dari GET
        $search = $_GET['search'] ?? '';
        $statusFilter = $_GET['status'] ?? []; // Array dari checkbox
        $dateFilter = $_GET['date_filter'] ?? ''; // 7, 30, 60 hari
        
        // Convert status filter jika string (dari checkbox)
        if (!empty($_GET['status'])) {
            if (is_string($_GET['status'])) {
                $statusFilter = explode(',', $_GET['status']);
            } else {
                $statusFilter = $_GET['status'];
            }
        }
        
        // Get data dengan filter
        $pengajuanCuti = $this->model->getWithFilters($search, $statusFilter, $dateFilter);
        $statistics = $this->model->getStatistics();
        
        $this->render('admin/leave/index', [
            'title' => 'Pengajuan Cuti',
            'pengajuanCuti' => $pengajuanCuti,
            'statistics' => $statistics,
            'currentSearch' => $search,
            'currentStatus' => $statusFilter,
            'currentDateFilter' => $dateFilter
        ]);
    }

    /**
     * Approve pengajuan cuti
     */
    public function approve() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/cuti');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            redirect('/admin/cuti');
        }

        // Update dengan approved_by
        if ($this->model->updateStatus($id, 'approved', $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil disetujui';
        } else {
            $_SESSION['error'] = 'Gagal menyetujui pengajuan cuti';
        }

        redirect('/admin/cuti');
    }

    /**
     * Reject pengajuan cuti
     */
    public function reject() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/cuti');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid';
            redirect('/admin/cuti');
        }

        $rejectionReason = trim($_POST['rejection_reason'] ?? '');
        
        // Validasi: rejection_reason wajib diisi
        if (empty($rejectionReason)) {
            $_SESSION['error'] = 'Alasan penolakan wajib diisi';
            redirect('/admin/cuti');
        }

        // Update dengan approved_by dan rejection_reason
        if ($this->model->updateStatus($id, 'rejected', $_SESSION['user_id'], $rejectionReason)) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil ditolak dengan alasan yang diberikan';
        } else {
            $_SESSION['error'] = 'Gagal menolak pengajuan cuti';
        }

        redirect('/admin/cuti');
    }

    /**
     * Menghapus pengajuan cuti
     */
    public function delete() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/cuti');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            redirect('/admin/cuti');
        }

        // Get data for file deletion
        $pengajuan = $this->model->find($id);
        
        if ($this->model->delete($id)) {
            // Delete file if exists (kompatibel dengan attachment_file)
            if (!empty($pengajuan['attachment_file'])) {
                $this->deleteDocument($pengajuan['attachment_file']);
            }
            $_SESSION['success'] = 'Pengajuan cuti berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengajuan cuti';
        }

        redirect('/admin/cuti');
    }
}

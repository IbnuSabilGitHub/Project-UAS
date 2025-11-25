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
     * Hapus file dokumen
     * 
     * @param string $fileName
     * @return bool
     */
    private function deleteDocument($fileName) {
        if (empty($fileName)) {
            return true;
        }

        $fullPath = __DIR__ . '/../../public/uploads/leave_attachments/' . $fileName;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return true;
    }

    /**
     * Menampilkan daftar pengajuan cuti
     */
    public function index() {
        $this->ensureAdmin();
        $pengajuanCuti = $this->model->allWithKaryawan();
        $statistics = $this->model->getStatistics();
        
        $this->render('cuti/index', [
            'title' => 'Pengajuan Cuti',
            'pengajuanCuti' => $pengajuanCuti,
            'statistics' => $statistics
        ]);
    }

    /**
     * Menampilkan form tambah pengajuan cuti (sementara, untuk demo admin)
     */
    public function create() {
        $this->ensureAdmin();
        $karyawans = $this->karyawanModel->all();
        
        $this->render('cuti/form', [
            'title' => 'Tambah Pengajuan Cuti',
            'pengajuan' => null,
            'karyawans' => $karyawans
        ]);
    }

    /**
     * Menyimpan pengajuan cuti baru
     */
    public function store() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/cuti');
        }

        $data = [
            'karyawan_id' => (int)($_POST['karyawan_id'] ?? 0),
            'leave_type' => trim($_POST['leave_type'] ?? 'annual'),
            'start_date' => trim($_POST['start_date'] ?? ''),
            'end_date' => trim($_POST['end_date'] ?? ''),
            'reason' => trim($_POST['reason'] ?? ''),
            'status' => $_POST['status'] ?? 'pending'
        ];

        // Validasi
        $errors = [];
        if (empty($data['karyawan_id'])) {
            $errors[] = 'Karyawan harus dipilih';
        }
        if (empty($data['start_date'])) {
            $errors[] = 'Tanggal mulai harus diisi';
        }
        if (empty($data['end_date'])) {
            $errors[] = 'Tanggal selesai harus diisi';
        }
        if (empty($data['reason'])) {
            $errors[] = 'Alasan cuti harus diisi';
        }
        
        // Validasi tanggal & hitung total hari
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = new DateTime($data['start_date']);
            $endDate = new DateTime($data['end_date']);
            if ($endDate < $startDate) {
                $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai';
            }
            $data['total_days'] = $this->model->calculateDays($data['start_date'], $data['end_date']);
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            redirect('/admin/cuti/create');
        }

        // Handle file upload
        $data['attachment_file'] = null;
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->uploadDocument($_FILES['document']);
            if ($uploadResult === false) {
                redirect('/admin/cuti/create');
            }
            $data['attachment_file'] = $uploadResult;
        }

        $insertId = $this->model->create($data);
        if ($insertId) {
            $_SESSION['success'] = "Pengajuan cuti berhasil dibuat ({$data['total_days']} hari)";
        } else {
            $_SESSION['error'] = 'Gagal menyimpan pengajuan cuti';
        }

        redirect('/admin/cuti');
    }

    /**
     * Menampilkan form edit pengajuan cuti (sementara, untuk demo admin)
     */
    public function edit() {
        $this->ensureAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            redirect('/admin/cuti');
        }

        $pengajuan = $this->model->find($id);
        if (!$pengajuan) {
            $_SESSION['error'] = 'Data pengajuan cuti tidak ditemukan';
            redirect('/admin/cuti');
        }

        $karyawans = $this->karyawanModel->all();

        $this->render('cuti/form', [
            'title' => 'Edit Pengajuan Cuti',
            'pengajuan' => $pengajuan,
            'karyawans' => $karyawans
        ]);
    }

    /**
     * Update pengajuan cuti (sementara, untuk demo admin)
     */
    public function update() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/cuti');
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            redirect('/admin/cuti');
        }

        $data = [
            'karyawan_id' => (int)($_POST['karyawan_id'] ?? 0),
            'leave_type' => trim($_POST['leave_type'] ?? 'annual'),
            'start_date' => trim($_POST['start_date'] ?? ''),
            'end_date' => trim($_POST['end_date'] ?? ''),
            'reason' => trim($_POST['reason'] ?? ''),
            'status' => $_POST['status'] ?? 'pending'
        ];

        // Validasi
        $errors = [];
        if (empty($data['karyawan_id'])) {
            $errors[] = 'Karyawan harus dipilih';
        }
        if (empty($data['start_date'])) {
            $errors[] = 'Tanggal mulai harus diisi';
        }
        if (empty($data['end_date'])) {
            $errors[] = 'Tanggal selesai harus diisi';
        }
        if (empty($data['reason'])) {
            $errors[] = 'Alasan cuti harus diisi';
        }

        // Validasi tanggal & hitung total hari
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = new DateTime($data['start_date']);
            $endDate = new DateTime($data['end_date']);
            if ($endDate < $startDate) {
                $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai';
            }
            $data['total_days'] = $this->model->calculateDays($data['start_date'], $data['end_date']);
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            redirect('/admin/cuti/edit?id=' . $id);
        }

        // Get existing data for file handling
        $existingData = $this->model->find($id);
        $data['attachment_file'] = $existingData['attachment_file'] ?? null;

        // Handle file upload
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->uploadDocument($_FILES['document']);
            if ($uploadResult === false) {
                redirect('/admin/cuti/edit?id=' . $id);
            }
            // Delete old file if exists
            if (!empty($existingData['attachment_file'])) {
                $this->deleteDocument($existingData['attachment_file']);
            }
            $data['attachment_file'] = $uploadResult;
        }

        if ($this->model->update($id, $data)) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil diperbarui';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui pengajuan cuti';
        }

        redirect('/admin/cuti');
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
            redirect('/admin/cuti');
        }

        $rejectionReason = trim($_POST['rejection_reason'] ?? 'Ditolak oleh admin');

        // Update dengan approved_by dan rejection_reason
        if ($this->model->updateStatus($id, 'rejected', $_SESSION['user_id'], $rejectionReason)) {
            $_SESSION['success'] = 'Pengajuan cuti berhasil ditolak';
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

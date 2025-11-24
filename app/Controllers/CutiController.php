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
     * Upload file PDF dokumen pendukung
     * 
     * @param array $file File dari $_FILES
     * @return string|false Path file jika berhasil, false jika gagal
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

        // Validasi tipe file (hanya PDF)
        $allowedTypes = ['application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['error'] = 'Hanya file PDF yang diperbolehkan';
            return false;
        }

        // Validasi ukuran file (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Ukuran file maksimal 5MB';
            return false;
        }

        // Generate nama file unik
        $uploadDir = __DIR__ . '/../../public/uploads/cuti/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = 'pdf';
        $fileName = 'cuti_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $fileName;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return 'uploads/cuti/' . $fileName; // Return relative path
        }

        $_SESSION['error'] = 'Gagal mengupload file';
        return false;
    }

    /**
     * Hapus file dokumen
     * 
     * @param string $filePath
     * @return bool
     */
    private function deleteDocument($filePath) {
        if (empty($filePath)) {
            return true;
        }

        $fullPath = __DIR__ . '/../../public/' . $filePath;
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
        
        // Validasi tanggal
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = new DateTime($data['start_date']);
            $endDate = new DateTime($data['end_date']);
            if ($endDate < $startDate) {
                $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            redirect('/admin/cuti/create');
        }

        // Handle file upload
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->uploadDocument($_FILES['document']);
            if ($uploadResult === false) {
                redirect('/admin/cuti/create');
            }
            $data['document_path'] = $uploadResult;
        }

        $insertId = $this->model->create($data);
        if ($insertId) {
            $days = $this->model->calculateDays($data['start_date'], $data['end_date']);
            $_SESSION['success'] = "Pengajuan cuti berhasil dibuat ({$days} hari)";
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

        // Validasi tanggal
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            $startDate = new DateTime($data['start_date']);
            $endDate = new DateTime($data['end_date']);
            if ($endDate < $startDate) {
                $errors[] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            redirect('/admin/cuti/edit?id=' . $id);
        }

        // Get existing data for file handling
        $existingData = $this->model->find($id);
        $data['document_path'] = $existingData['document_path'] ?? null;

        // Handle file upload
        if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->uploadDocument($_FILES['document']);
            if ($uploadResult === false) {
                redirect('/admin/cuti/edit?id=' . $id);
            }
            // Delete old file if exists
            if (!empty($existingData['document_path'])) {
                $this->deleteDocument($existingData['document_path']);
            }
            $data['document_path'] = $uploadResult;
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

        if ($this->model->updateStatus($id, 'approved')) {
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

        if ($this->model->updateStatus($id, 'rejected')) {
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
            // Delete file if exists
            if (!empty($pengajuan['document_path'])) {
                $this->deleteDocument($pengajuan['document_path']);
            }
            $_SESSION['success'] = 'Pengajuan cuti berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pengajuan cuti';
        }

        redirect('/admin/cuti');
    }


}

<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/LeaveRequest.php';
require_once __DIR__ . '/../Models/PengajuanCuti.php';

/**
 * FileController - Mengelola view/preview file dengan authentication
 * 
 * File disimpan di storage/ untuk keamanan
 * Menggunakan User Model untuk authorization (MVC compliant)
 */
class FileController extends BaseController {
    
    public function __construct() {
        parent::__construct(); // Initialize userModel
    }
    
    /**
     * Cek apakah user berhak akses file cuti ini
     * Menggunakan User Model (MVC compliant)
     * 
     * @param array $leave Data pengajuan cuti
     * @return bool
     */
    private function canAccessFile($leave) {
        $this->startSession();
        
        $role = $_SESSION['role'] ?? '';
        $userId = $_SESSION['user_id'] ?? 0;

        // Admin bisa akses semua file
        if ($role === 'admin' || $role === 'super_admin') {
            return true;
        }

        // Karyawan hanya bisa akses file miliknya sendiri
        if ($role === 'karyawan') {
            // Gunakan User Model untuk get karyawan_id (MVC compliant)
            $karyawanId = $this->userModel->getKaryawanId($userId);
            
            if ($karyawanId && $karyawanId == $leave['karyawan_id']) {
                return true;
            }
        }

        return false;
    }

    /**
     * View/preview file attachment cuti
     * 
     * @param int $leaveId ID pengajuan cuti
     */
    public function viewLeaveAttachment($leaveId) {
        // Gunakan method dari BaseController
        $this->ensureAuthenticated();

        // Validasi ID
        $id = (int)$leaveId;
        if ($id <= 0) {
            http_response_code(400);
            die('Bad Request: Invalid ID');
        }

        // Ambil data pengajuan cuti
        $model = new PengajuanCuti();
        $leave = $model->find($id);

        if (!$leave) {
            http_response_code(404);
            die('Not Found: Pengajuan cuti tidak ditemukan');
        }

        // Cek permission
        if (!$this->canAccessFile($leave)) {
            $this->show403();
        }

        // Cek apakah ada file
        if (empty($leave['attachment_file'])) {
            http_response_code(404);
            die('Not Found: File tidak tersedia');
        }

        // Path ke file di storage
        $filePath = __DIR__ . '/../../storage/leave_attachments/' . $leave['attachment_file'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            die('Not Found: File tidak ditemukan di server');
        }

        // Detect MIME type dengan fallback
        $mimeType = 'application/octet-stream'; // Default
        
        if (function_exists('finfo_open')) {
            // Gunakan finfo jika tersedia
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $detectedMime = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            
            if ($detectedMime) {
                $mimeType = $detectedMime;
            }
        } else {
            // Fallback: detect dari extension
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $mimeType = match($extension) {
                'pdf' => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                default => 'application/octet-stream'
            };
        }

        // Set headers untuk download
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . basename($leave['attachment_file']) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: public');

        // Output file
        readfile($filePath);
        exit;
    }
}

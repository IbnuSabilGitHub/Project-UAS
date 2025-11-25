<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/LeaveRequest.php';
require_once __DIR__ . '/../Models/PengajuanCuti.php';

/**
 * Controller untuk view/preview file
 * File disimpan di storage/ untuk keamanan
 */
class FileController {
    
    /**
     * Pastikan user sudah login
     */
    private function ensureAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            die('Unauthorized: Silakan login terlebih dahulu');
        }
    }

    /**
     * Cek apakah user berhak akses file cuti ini
     * 
     * @param array $leave Data pengajuan cuti
     * @return bool
     */
    private function canAccessFile($leave) {
        $role = $_SESSION['role'] ?? '';
        $userId = $_SESSION['user_id'] ?? 0;

        // Admin bisa akses semua file
        if ($role === 'admin' || $role === 'super_admin') {
            return true;
        }

        // Karyawan hanya bisa akses file miliknya sendiri
        if ($role === 'karyawan') {
            // Get karyawan_id dari user
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result && $result['karyawan_id'] == $leave['karyawan_id']) {
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
            http_response_code(403);
            die('Forbidden: Anda tidak memiliki akses ke file ini');
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

        // Detect MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

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

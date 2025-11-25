<?php
require_once __DIR__ . '/../Core/Database.php';

class LeaveRequest {
    private $conn;
    private $uploadDir;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->uploadDir = dirname(dirname(__DIR__)) . '/storage/leave_attachments/';
    }

    /**
     * Buat pengajuan cuti baru
     */
    public function create($data, $file = null) {
        $attachmentFile = null;

        // Handle file upload jika ada
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $attachmentFile = $this->uploadFile($file);
            if (!$attachmentFile) {
                return ['success' => false, 'message' => 'Gagal upload file'];
            }
        }

        $stmt = $this->conn->prepare("
            INSERT INTO leave_requests 
            (karyawan_id, leave_type, start_date, end_date, total_days, reason, attachment_file, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->bind_param(
            'issssss',
            $data['karyawan_id'],
            $data['leave_type'],
            $data['start_date'],
            $data['end_date'],
            $data['total_days'],
            $data['reason'],
            $attachmentFile
        );

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->insert_id];
        }
        
        // Hapus file jika insert gagal
        if ($attachmentFile) {
            @unlink($this->uploadDir . $attachmentFile);
        }
        
        return ['success' => false, 'message' => 'Gagal menyimpan pengajuan cuti'];
    }

    /**
     * Upload file attachment
     */
    private function uploadFile($file) {
        // Validasi ukuran (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }

        // Validasi tipe file (hanya PDF, JPG, PNG)
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'leave_' . uniqid() . '_' . time() . '.' . $extension;
        $destination = $this->uploadDir . $filename;

        // Buat directory jika belum ada
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return false;
    }

    /**
     * Ambil semua pengajuan cuti karyawan
     */
    public function getByKaryawan($karyawanId) {
        $stmt = $this->conn->prepare("
            SELECT lr.*, 
                   u.username as approver_name
            FROM leave_requests lr
            LEFT JOIN users u ON lr.approved_by = u.id
            WHERE lr.karyawan_id = ?
            ORDER BY lr.created_at DESC
        ");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Ambil semua pengajuan cuti (untuk admin)
     */
    public function getAll($status = null) {
        $sql = "
            SELECT lr.*, 
                   k.nik, k.name as karyawan_name,
                   u.username as approver_name
            FROM leave_requests lr
            JOIN karyawan k ON lr.karyawan_id = k.id
            LEFT JOIN users u ON lr.approved_by = u.id
        ";
        
        if ($status) {
            $sql .= " WHERE lr.status = ?";
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($status) {
            $stmt->bind_param('s', $status);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Ambil detail pengajuan cuti
     */
    public function find($id) {
        $stmt = $this->conn->prepare("
            SELECT lr.*, 
                   k.nik, k.name as karyawan_name, k.email,
                   u.username as approver_name
            FROM leave_requests lr
            JOIN karyawan k ON lr.karyawan_id = k.id
            LEFT JOIN users u ON lr.approved_by = u.id
            WHERE lr.id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Approve pengajuan cuti (admin)
     */
    public function approve($id, $approvedBy) {
        $stmt = $this->conn->prepare("
            UPDATE leave_requests 
            SET status = 'approved', 
                approved_by = ?, 
                approved_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param('ii', $approvedBy, $id);
        return $stmt->execute();
    }

    /**
     * Reject pengajuan cuti (admin)
     */
    public function reject($id, $approvedBy, $reason) {
        $stmt = $this->conn->prepare("
            UPDATE leave_requests 
            SET status = 'rejected', 
                approved_by = ?, 
                approved_at = NOW(),
                rejection_reason = ?
            WHERE id = ?
        ");
        $stmt->bind_param('isi', $approvedBy, $reason, $id);
        return $stmt->execute();
    }

    /**
     * Hapus pengajuan cuti (beserta file)
     */
    public function delete($id) {
        // Ambil data untuk hapus file
        $leave = $this->find($id);
        
        $stmt = $this->conn->prepare("DELETE FROM leave_requests WHERE id = ?");
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            // Hapus file jika ada
            if ($leave && $leave['attachment_file']) {
                @unlink($this->uploadDir . $leave['attachment_file']);
            }
            return true;
        }
        
        return false;
    }

    /**
     * Hitung total hari cuti yang sudah diambil tahun ini
     */
    public function getTotalDaysThisYear($karyawanId) {
        $year = date('Y');
        $stmt = $this->conn->prepare("
            SELECT COALESCE(SUM(total_days), 0) as total
            FROM leave_requests
            WHERE karyawan_id = ?
            AND status = 'approved'
            AND YEAR(start_date) = ?
        ");
        $stmt->bind_param('ii', $karyawanId, $year);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }

    /**
     * Cek apakah ada overlap tanggal cuti
     */
    public function hasOverlap($karyawanId, $startDate, $endDate, $excludeId = null) {
        $sql = "
            SELECT COUNT(*) as count
            FROM leave_requests
            WHERE karyawan_id = ?
            AND status IN ('pending', 'approved')
            AND (
                (start_date <= ? AND end_date >= ?)
                OR (start_date <= ? AND end_date >= ?)
                OR (start_date >= ? AND end_date <= ?)
            )
        ";
        
        if ($excludeId) {
            $sql .= " AND id != ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if ($excludeId) {
            $stmt->bind_param('issssssi', $karyawanId, $startDate, $startDate, $endDate, $endDate, $startDate, $endDate, $excludeId);
        } else {
            $stmt->bind_param('issssss', $karyawanId, $startDate, $startDate, $endDate, $endDate, $startDate, $endDate);
        }
        
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0;
    }
}

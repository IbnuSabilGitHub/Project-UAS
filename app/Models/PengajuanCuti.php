<?php
require_once __DIR__ . '/../Core/Database.php';

/**
 * Model untuk mengelola pengajuan cuti
 * Kompatibel dengan tabel leave_requests dari fitur karyawan
 */
class PengajuanCuti {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Mengambil semua data pengajuan cuti beserta info karyawan
     * 
     * @return array
     */
    public function allWithKaryawan() {
        $sql = "SELECT lr.*, k.nik, k.name AS karyawan_name, k.position,
                       u.email as approver_email
                FROM leave_requests lr
                INNER JOIN karyawan k ON lr.karyawan_id = k.id
                LEFT JOIN users u ON lr.approved_by = u.id
                ORDER BY lr.created_at DESC";
        $result = $this->conn->query($sql);
        $rows = [];
        if ($result) {
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    /**
     * Mengambil pengajuan cuti berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find($id) {
        $stmt = $this->conn->prepare("SELECT lr.*, k.nik, k.name AS karyawan_name, k.position,
                                              u.email as approver_email
                                        FROM leave_requests lr
                                        INNER JOIN karyawan k ON lr.karyawan_id = k.id
                                        LEFT JOIN users u ON lr.approved_by = u.id
                                        WHERE lr.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    /**
     * Mengambil pengajuan cuti berdasarkan karyawan_id
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getByKaryawan($karyawanId) {
        $stmt = $this->conn->prepare("SELECT * FROM leave_requests WHERE karyawan_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }



    /**
     * Membuat pengajuan cuti baru
     * 
     * @param array $data
     * @return int|false ID pengajuan baru atau false jika gagal
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO leave_requests 
                                        (karyawan_id, leave_type, start_date, end_date, total_days, reason, attachment_file, status) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $status = $data['status'] ?? 'pending';
        $leaveType = $data['leave_type'] ?? 'annual';
        $attachmentFile = $data['attachment_file'] ?? null;
        $totalDays = $data['total_days'];
        
        $stmt->bind_param('isssssss', 
            $data['karyawan_id'], 
            $leaveType,
            $data['start_date'], 
            $data['end_date'],
            $totalDays,
            $data['reason'],
            $attachmentFile,
            $status
        );
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    /**
     * Update status pengajuan cuti
     * 
     * @param int $id
     * @param string $status (pending|approved|rejected)
     * @param int $approvedBy User ID yang approve/reject
     * @param string $rejectionReason Alasan jika ditolak
     * @return bool
     */
    public function updateStatus($id, $status, $approvedBy = null, $rejectionReason = null) {
        $allowedStatus = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowedStatus)) {
            return false;
        }
        
        if ($status === 'rejected' && $rejectionReason) {
            $stmt = $this->conn->prepare("UPDATE leave_requests 
                                          SET status = ?, approved_by = ?, approved_at = NOW(), rejection_reason = ? 
                                          WHERE id = ?");
            $stmt->bind_param('sisi', $status, $approvedBy, $rejectionReason, $id);
        } else if ($approvedBy) {
            $stmt = $this->conn->prepare("UPDATE leave_requests 
                                          SET status = ?, approved_by = ?, approved_at = NOW() 
                                          WHERE id = ?");
            $stmt->bind_param('sii', $status, $approvedBy, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
            $stmt->bind_param('si', $status, $id);
        }
        
        return $stmt->execute();
    }

    /**
     * Update data pengajuan cuti
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE leave_requests 
                                        SET karyawan_id = ?, leave_type = ?, start_date = ?, end_date = ?, 
                                            total_days = ?, reason = ?, attachment_file = ?, status = ? 
                                        WHERE id = ?");
        $status = $data['status'] ?? 'pending';
        $leaveType = $data['leave_type'] ?? 'annual';
        $attachmentFile = $data['attachment_file'] ?? null;
        $totalDays = $data['total_days'];
        
        $stmt->bind_param('isssssssi', 
            $data['karyawan_id'],
            $leaveType,
            $data['start_date'], 
            $data['end_date'],
            $totalDays,
            $data['reason'],
            $attachmentFile,
            $status,
            $id
        );
        return $stmt->execute();
    }

    /**
     * Menghapus pengajuan cuti
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM leave_requests WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Menghitung total hari cuti (termasuk start dan end date)
     * 
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function calculateDays($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $diff = $start->diff($end);
        return $diff->days + 1; // +1 untuk menghitung hari pertama
    }

    /**
     * Mengambil data pengajuan cuti dengan filter
     * 
     * @param string $search - Nama karyawan
     * @param array $statusFilter - Array status ['approved', 'pending', 'rejected']
     * @param string $dateFilter - Range tanggal (7, 30, 60 hari)
     * @return array
     */
    public function getWithFilters($search = '', $statusFilter = [], $dateFilter = '') {
        $sql = "SELECT lr.*, k.nik, k.name AS karyawan_name, k.position,
                       u.email as approver_name
                FROM leave_requests lr
                INNER JOIN karyawan k ON lr.karyawan_id = k.id
                LEFT JOIN users u ON lr.approved_by = u.id
                WHERE 1=1";
        
        $params = [];
        $types = '';
        
        // Filter search berdasarkan nama karyawan
        if (!empty($search)) {
            $sql .= " AND k.name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        
        // Filter berdasarkan status (checkbox - bisa multiple)
        if (!empty($statusFilter) && is_array($statusFilter)) {
            $placeholders = implode(',', array_fill(0, count($statusFilter), '?'));
            $sql .= " AND lr.status IN ($placeholders)";
            foreach ($statusFilter as $status) {
                $params[] = $status;
                $types .= 's';
            }
        }
        
        // Filter berdasarkan tanggal (last 7, 30, 60 hari)
        if (!empty($dateFilter) && in_array($dateFilter, ['7', '30', '60'])) {
            $sql .= " AND lr.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)";
            $params[] = (int)$dateFilter;
            $types .= 'i';
        }
        
        $sql .= " ORDER BY lr.created_at DESC";
        
        // Execute query
        if (!empty($params)) {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($sql);
        }
        
        $rows = [];
        if ($result) {
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    /**
     * Mengambil statistik pengajuan cuti
     * 
     * @return array
     */
    public function getStatistics() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM leave_requests";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : [];
    }

    /**
     * Menghitung jumlah pengajuan cuti dengan status pending
     * 
     * @return int
     */
    public function countPending() {
        $sql = "SELECT COUNT(*) as total FROM leave_requests WHERE status = 'pending'";
        $result = $this->conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return (int)$row['total'];
        }
        return 0;
    }

    /**
     * Mengambil statistik cuti untuk karyawan tertentu
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getEmployeeStats($karyawanId) {
        $stats = [];

        // Total pengajuan cuti
        $stmt = $this->conn->prepare("SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN status = 'approved' THEN total_days ELSE 0 END) as total_days_approved
                FROM leave_requests WHERE karyawan_id = ?");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = $result->fetch_assoc();

        // Cuti berdasarkan tipe
        $stmt = $this->conn->prepare("SELECT leave_type, COUNT(*) as count, SUM(total_days) as total_days 
                FROM leave_requests 
                WHERE karyawan_id = ? AND status = 'approved' 
                GROUP BY leave_type");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['by_type'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['by_type'][$row['leave_type']] = [
                'count' => (int)$row['count'],
                'total_days' => (int)$row['total_days']
            ];
        }

        // Cuti tahun ini
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total, SUM(total_days) as total_days 
                FROM leave_requests 
                WHERE karyawan_id = ? 
                AND YEAR(start_date) = YEAR(CURRENT_DATE()) 
                AND status = 'approved'");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $result = $stmt->get_result();
        $thisYear = $result->fetch_assoc();
        $stats['tahun_ini'] = $thisYear;

        return $stats;
    }
}

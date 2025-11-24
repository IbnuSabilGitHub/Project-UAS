<?php
require_once __DIR__ . '/../Core/Database.php';

class Attendance {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Check apakah karyawan sudah check-in hari ini
     */
    public function hasCheckedInToday($karyawanId) {
        $today = date('Y-m-d');
        $stmt = $this->conn->prepare("
            SELECT id, check_in, check_out, notes, status, created_at
            FROM attendance 
            WHERE karyawan_id = ? 
            AND DATE(check_in) = ? 
            LIMIT 1
        ");
        $stmt->bind_param('is', $karyawanId, $today);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Check-in karyawan
     */
    public function checkIn($karyawanId, $notes = '') {
        // Cek sudah check-in hari ini?
        if ($this->hasCheckedInToday($karyawanId)) {
            return false;
        }

        $checkIn = date('Y-m-d H:i:s');
        $status = 'present';
        
        // Cek kalau terlambat (misal setelah jam 09:00)
        $hour = (int)date('H');
        $minute = (int)date('i');
        if ($hour > 9 || ($hour == 9 && $minute > 0)) {
            $status = 'late';
        }

        $stmt = $this->conn->prepare("
            INSERT INTO attendance (karyawan_id, check_in, notes, status, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('isss', $karyawanId, $checkIn, $notes, $status);
        return $stmt->execute();
    }

    /**
     * Check-out karyawan
     */
    public function checkOut($karyawanId, $notes = '') {
        $today = date('Y-m-d');
        $checkOut = date('Y-m-d H:i:s');
        
        $stmt = $this->conn->prepare("
            UPDATE attendance 
            SET check_out = ?, notes = CONCAT(COALESCE(notes, ''), ?, ?) 
            WHERE karyawan_id = ? 
            AND DATE(check_in) = ? 
            AND check_out IS NULL
        ");
        $separator = empty($notes) ? '' : ' | ';
        $stmt->bind_param('sssss', $checkOut, $separator, $notes, $karyawanId, $today);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    /**
     * Ambil riwayat absensi karyawan
     */
    public function getHistory($karyawanId, $limit = 30) {
        $stmt = $this->conn->prepare("
            SELECT * FROM attendance 
            WHERE karyawan_id = ? 
            ORDER BY check_in DESC 
            LIMIT ?
        ");
        $stmt->bind_param('ii', $karyawanId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Ambil semua absensi (untuk admin)
     */
    public function getAll($limit = 100) {
        $sql = "
            SELECT a.*, k.nik, k.name 
            FROM attendance a
            JOIN karyawan k ON a.karyawan_id = k.id
            ORDER BY a.check_in DESC
            LIMIT ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Statistik kehadiran karyawan bulan ini
     */
    public function getMonthlyStats($karyawanId) {
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'half_day' THEN 1 ELSE 0 END) as half_day
            FROM attendance
            WHERE karyawan_id = ?
            AND DATE(check_in) BETWEEN ? AND ?
        ");
        $stmt->bind_param('iss', $karyawanId, $startOfMonth, $endOfMonth);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ===== ADMIN METHODS =====

    /**
     * Ambil semua absensi dengan filter untuk admin
     */
    public function getAllWithFilter($date = '', $searchName = '', $status = '', $limit = 20, $offset = 0) {
        $sql = "
            SELECT a.*, k.nik, k.name, k.position
            FROM attendance a
            JOIN karyawan k ON a.karyawan_id = k.id
            WHERE 1=1
        ";
        
        $types = '';
        $params = [];

        if ($date) {
            $sql .= " AND DATE(a.check_in) = ?";
            $types .= 's';
            $params[] = $date;
        }

        if ($searchName) {
            $sql .= " AND k.name LIKE ?";
            $types .= 's';
            $params[] = '%' . $searchName . '%';
        }

        if ($status) {
            $sql .= " AND a.status = ?";
            $types .= 's';
            $params[] = $status;
        }

        $sql .= " ORDER BY a.check_in DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->conn->prepare($sql);
        
        if ($types) {
            $stmt->bind_param($types, ...$params);
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
     * Hitung total record dengan filter untuk pagination
     */
    public function countAllWithFilter($date = '', $searchName = '', $status = '') {
        $sql = "
            SELECT COUNT(*) as total
            FROM attendance a
            JOIN karyawan k ON a.karyawan_id = k.id
            WHERE 1=1
        ";
        
        $types = '';
        $params = [];

        if ($date) {
            $sql .= " AND DATE(a.check_in) = ?";
            $types .= 's';
            $params[] = $date;
        }

        if ($searchName) {
            $sql .= " AND k.name LIKE ?";
            $types .= 's';
            $params[] = '%' . $searchName . '%';
        }

        if ($status) {
            $sql .= " AND a.status = ?";
            $types .= 's';
            $params[] = $status;
        }

        $stmt = $this->conn->prepare($sql);
        
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    /**
     * Statistik absensi untuk admin
     */
    public function getAdminStats($date = '') {
        $sql = "
            SELECT 
                COUNT(DISTINCT a.karyawan_id) as total_employees,
                COUNT(*) as total_attendance,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN a.status = 'half_day' THEN 1 ELSE 0 END) as half_day,
                SUM(CASE WHEN a.check_out IS NULL THEN 1 ELSE 0 END) as not_checkout
            FROM attendance a
        ";

        if ($date) {
            $sql .= " WHERE DATE(a.check_in) = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('s', $date);
        } else {
            // Default: bulan ini
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $sql .= " WHERE DATE(a.check_in) BETWEEN ? AND ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $startOfMonth, $endOfMonth);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

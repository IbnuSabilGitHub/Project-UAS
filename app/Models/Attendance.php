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
}

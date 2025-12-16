<?php
require_once __DIR__ . '/../Core/Database.php';

/**
 * Attendance Model - Mengelola data absensi karyawan
 * 
 * Fitur: check-in, check-out, tracking status kehadiran,
 * statistik absensi, dan history
 */
class Attendance {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Check apakah karyawan sudah check-in hari ini
     * 
     * @param int $karyawanId
     * @return array|null
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
     * 
     * @param int $karyawanId
     * @param string $notes
     * @return bool
     */
    public function checkIn($karyawanId, $notes = '') {
        // Cek sudah check-in hari ini?
        if ($this->hasCheckedInToday($karyawanId)) {
            return false;
        }

        $checkIn = date('Y-m-d H:i:s');
        $status = 'present';
        
        // Aturan untuk menentukan status kehadiran:
        // - Jam kerja valid: 06:00 - 23:59 (di luar jam ini = terlambat)
        // - Hadir tepat waktu (present): Check-in jam 06:00 - 09:00
        // - Half day: Check-in antara jam 09:01 sampai 09:15 (toleransi 15 menit)
        // - Terlambat (late): Check-in setelah jam 09:15 atau sebelum jam 06:00

        $hour = (int)date('H');
        $minute = (int)date('i');
        
        // Konversi ke menit untuk perbandingan yang lebih akurat
        $currentTimeInMinutes = ($hour * 60) + $minute;
        $workStartTime = (6 * 60); // 06:00 = 360 menit (batas awal jam kerja)
        $onTimeLimit = (9 * 60); // 09:00 = 540 menit
        $toleranceLimit = (9 * 60) + 15; // 09:15 = 555 menit
        
        if ($currentTimeInMinutes < $workStartTime) {
            // Sebelum jam 06:00 (tengah malam 00:00 - 05:59) = terlambat
            $status = 'late';
        } elseif ($currentTimeInMinutes > $toleranceLimit) {
            // Setelah jam 09:15 = terlambat
            $status = 'late';
        } elseif ($currentTimeInMinutes > $onTimeLimit) {
            // Antara 09:01 - 09:15 = half day (toleransi)
            $status = 'half_day';
        } else {
            // Jam 06:00 - 09:00 = hadir tepat waktu
            $status = 'present';
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
     * 
     * @param int $karyawanId
     * @param string $notes
     * @return bool
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
     * 
     * @param int $karyawanId
     * @param int $limit
     * @return array
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
     * @param int $limit
     * @return array
     * 
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
     * 
     * @param int $karyawanId
     * @return array
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


    
    // ADMIN METHODS

    /**
     * Ambil semua absensi dengan filter untuk admin
     * 
     * @param string $startDate Start date for range filter (Y-m-d)
     * @param string $endDate End date for range filter (Y-m-d)
     * @param string $searchName Search by name or NIK
     * @param array $statusFilter Array of status to filter
     * @return array
     */
    public function getWithFilters($startDate = '', $endDate = '', $searchName = '', $statusFilter = []) {
        $sql = "
            SELECT a.*, k.nik, k.name, k.position
            FROM attendance a
            JOIN karyawan k ON a.karyawan_id = k.id
            WHERE 1=1
        ";
        
        $types = '';
        $params = [];

        // Date range filter
        if ($startDate && $endDate) {
            $sql .= " AND DATE(a.check_in) BETWEEN ? AND ?";
            $types .= 'ss';
            $params[] = $startDate;
            $params[] = $endDate;
        }

        if ($searchName) {
            $sql .= " AND (k.name LIKE ? OR k.nik LIKE ?)";
            $types .= 'ss';
            $params[] = '%' . $searchName . '%';
            $params[] = '%' . $searchName . '%';
        }

        // Filter status: jika array tidak kosong dan tidak berisi semua status
        if (!empty($statusFilter) && count($statusFilter) < 3) {
            $placeholders = str_repeat('?,', count($statusFilter) - 1) . '?';
            $sql .= " AND a.status IN ($placeholders)";
            $types .= str_repeat('s', count($statusFilter));
            $params = array_merge($params, $statusFilter);
        }

        $sql .= " ORDER BY a.check_in DESC";

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
     * Statistik absensi untuk admin
     * 
     * @param string $startDate Start date for range (Y-m-d)
     * @param string $endDate End date for range (Y-m-d)
     * @return array
     */
    public function getAdminStats($startDate = '', $endDate = '') {
        $sql = "
            SELECT 
                COUNT(DISTINCT a.karyawan_id) as total_employees,
                COUNT(*) as total_attendance,
                SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN a.status = 'half_day' THEN 1 ELSE 0 END) as half_day,
                SUM(CASE WHEN a.check_out IS NULL THEN 1 ELSE 0 END) as not_checkout
            FROM attendance a
            WHERE 1=1
        ";

        $types = '';
        $params = [];

        if ($startDate && $endDate) {
            $sql .= " AND DATE(a.check_in) BETWEEN ? AND ?";
            $types = 'ss';
            $params = [$startDate, $endDate];
        }

        $stmt = $this->conn->prepare($sql);
        
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Statistik absensi untuk karyawan tertentu
     * 
     * @param int $karyawanId
     * @return array
     */
    public function getEmployeeStats($karyawanId) {
        $stats = [];

        // Absensi bulan ini
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_attendance,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'half_day' THEN 1 ELSE 0 END) as half_day,
                SUM(CASE WHEN check_out IS NULL THEN 1 ELSE 0 END) as not_checkout
            FROM attendance 
            WHERE karyawan_id = ? 
            AND DATE(check_in) BETWEEN ? AND ?
        ");
        $stmt->bind_param('iss', $karyawanId, $startOfMonth, $endOfMonth);
        $stmt->execute();
        $stats['bulan_ini'] = $stmt->get_result()->fetch_assoc();

        // Total absensi keseluruhan
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_attendance,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'half_day' THEN 1 ELSE 0 END) as half_day
            FROM attendance 
            WHERE karyawan_id = ?
        ");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $stats['keseluruhan'] = $stmt->get_result()->fetch_assoc();

        // Absensi 7 hari terakhir
        $stmt = $this->conn->prepare("
            SELECT DATE(check_in) as tanggal, status, check_in, check_out
            FROM attendance 
            WHERE karyawan_id = ? 
            AND DATE(check_in) >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)
            ORDER BY check_in DESC
        ");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['tujuh_hari_terakhir'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['tujuh_hari_terakhir'][] = $row;
        }

        return $stats;
    }
}

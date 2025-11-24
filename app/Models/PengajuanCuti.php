<?php
require_once __DIR__ . '/../Core/Database.php';

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
        $sql = "SELECT pc.*, k.nik, k.name AS karyawan_name, k.position 
                FROM pengajuan_cuti pc
                INNER JOIN karyawan k ON pc.karyawan_id = k.id
                ORDER BY pc.created_at DESC";
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
        $stmt = $this->conn->prepare("SELECT pc.*, k.nik, k.name AS karyawan_name, k.position 
                                        FROM pengajuan_cuti pc
                                        INNER JOIN karyawan k ON pc.karyawan_id = k.id
                                        WHERE pc.id = ? LIMIT 1");
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
        $stmt = $this->conn->prepare("SELECT * FROM pengajuan_cuti WHERE karyawan_id = ? ORDER BY created_at DESC");
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
     * Membuat pengajuan cuti baru (sementara, untuk demo admin)
     * 
     * @param array $data
     * @return int|false ID pengajuan baru atau false jika gagal
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO pengajuan_cuti (karyawan_id, start_date, end_date, reason, document_path, status) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
        $status = $data['status'] ?? 'pending';
        $documentPath = $data['document_path'] ?? null;
        $stmt->bind_param('isssss', 
            $data['karyawan_id'], 
            $data['start_date'], 
            $data['end_date'], 
            $data['reason'],
            $documentPath,
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
     * @return bool
     */
    public function updateStatus($id, $status) {
        $allowedStatus = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $allowedStatus)) {
            return false;
        }
        
        $stmt = $this->conn->prepare("UPDATE pengajuan_cuti SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
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
        $stmt = $this->conn->prepare("UPDATE pengajuan_cuti 
                                        SET karyawan_id = ?, start_date = ?, end_date = ?, reason = ?, document_path = ?, status = ? 
                                        WHERE id = ?");
        $status = $data['status'] ?? 'pending';
        $documentPath = $data['document_path'] ?? null;
        $stmt->bind_param('isssssi', 
            $data['karyawan_id'], 
            $data['start_date'], 
            $data['end_date'], 
            $data['reason'],
            $documentPath,
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
        $stmt = $this->conn->prepare("DELETE FROM pengajuan_cuti WHERE id = ?");
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
                FROM pengajuan_cuti";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : [];
    }
}

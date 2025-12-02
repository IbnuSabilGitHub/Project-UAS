<?php
require_once __DIR__ . '/../Core/Database.php';

class Karyawan
{
    private $conn;
    private $lastError = '';

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Mendapatkan pesan error terakhir
     * 
     * @return string
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Mengambil semua data karyawan
     * @return array
     */
    public function all()
    {
        $sql = "SELECT * FROM karyawan ORDER BY id DESC";
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
     * Mengambil semua data karyawan beserta info akun (jika ada)
     * 
     * @return array
     */
    public function allWithUser()
    {
        $sql = "SELECT k.*, u.id AS user_id, u.email, u.must_change_password, u.status AS user_status FROM karyawan k LEFT JOIN users u ON u.karyawan_id = k.id ORDER BY k.id DESC";
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
     * Mencari karyawan berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM karyawan WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    /**
     * Membuat data karyawan baru
     * 
     * @param array $data
     * @return int|false ID karyawan baru atau false jika gagal
     */
    public function create($data)
    {
        $this->lastError = '';
        $stmt = $this->conn->prepare("INSERT INTO karyawan (nik, name, email, phone, position, join_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $this->lastError = $this->conn->error;
            return false;
        }
        $stmt->bind_param('sssssss', $data['nik'], $data['name'], $data['email'], $data['phone'], $data['position'], $data['join_date'], $data['status']);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        $this->lastError = $stmt->error;
        return false;
    }

    /**
     * Memperbarui data karyawan sesuai ID
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->lastError = '';
        $stmt = $this->conn->prepare("UPDATE karyawan SET nik = ?, name = ?, email = ?, phone = ?, position = ?, join_date = ?, status = ? WHERE id = ?");
        if (!$stmt) {
            $this->lastError = $this->conn->error;
            return false;
        }
        $stmt->bind_param('sssssssi', $data['nik'], $data['name'], $data['email'], $data['phone'], $data['position'], $data['join_date'], $data['status'], $id);
        if ($stmt->execute()) {
            return true;
        }
        $this->lastError = $stmt->error;
        return false;
    }

    /**
     * Menghapus data karyawan sesuai ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM karyawan WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Membuat akun pengguna yang terhubung dengan data karyawan (wajib ganti password pertama login)
     * 
     * @param int $karyawanId
     * @param string $email
     * @param string $password
     * @param string $role
     * @return bool
     */
    public function createAccount($karyawanId, $email, $password, $role = 'karyawan')
    {
        if ($this->getUserByKaryawanId($karyawanId)) {
            return false; // sudah punya akun
        }
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $createdAt = date('Y-m-d H:i:s');
        $mustChange = 1;
        $status = 'active';
        $stmt = $this->conn->prepare("INSERT INTO users (email, password_hash, role, karyawan_id, status, must_change_password, password_last_changed, created_at) VALUES (?, ?, ?, ?, ?, ?, NULL, ?)");
        // 7 placeholders => types: s (email), s (hash), s (role), i (karyawan_id), s (status), i (must_change), s (created_at)
        $stmt->bind_param('sssisis', $email, $passwordHash, $role, $karyawanId, $status, $mustChange, $createdAt);
        return $stmt->execute();
    }

    /**
     * Ambil akun user berdasarkan karyawan_id
     * 
     * @param int $karyawanId
     * @return array|null
     */
    public function getUserByKaryawanId($karyawanId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE karyawan_id = ? LIMIT 1");
        $stmt->bind_param('i', $karyawanId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    /** Nonaktifkan karyawan (soft) + disable akun 
     * @param int $id
     * @return bool
     */
    public function deactivate($id)
    {
        // set employment_status resigned dan status inactive
        $stmt = $this->conn->prepare("UPDATE karyawan SET employment_status='resigned' WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        // nonaktifkan akun user jika ada
        $u = $this->getUserByKaryawanId($id);
        if ($u) {
            $stmt2 = $this->conn->prepare("UPDATE users SET status='disabled' WHERE karyawan_id = ?");
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
        }
        return $ok;
    }

    /** Hard delete karyawan + user account
     * @param int $id
     * @return bool
     */
    public function deleteWithUser($id)
    {
        $stmt1 = $this->conn->prepare("DELETE FROM users WHERE karyawan_id = ?");
        $stmt1->bind_param('i', $id);
        $stmt1->execute();
        $stmt2 = $this->conn->prepare("DELETE FROM karyawan WHERE id = ?");
        $stmt2->bind_param('i', $id);
        return $stmt2->execute();
    }

    /**
     * Mengambil statistik karyawan untuk dashboard
     * 
     * @return array
     */
    public function getStatistics()
    {
        $stats = [];

        // Total karyawan
        $result = $this->conn->query("SELECT COUNT(*) as total FROM karyawan");
        $stats['total_karyawan'] = $result->fetch_assoc()['total'];

        // Karyawan berdasarkan status
        $result = $this->conn->query("SELECT status, COUNT(*) as count FROM karyawan GROUP BY status");
        $stats['by_status'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['by_status'][$row['status']] = (int)$row['count'];
        }

        // Karyawan berdasarkan posisi/jabatan
        $result = $this->conn->query("SELECT position, COUNT(*) as count FROM karyawan GROUP BY position ORDER BY count DESC LIMIT 10");
        $stats['by_position'] = [];
        while ($row = $result->fetch_assoc()) {
            $stats['by_position'][$row['position']] = (int)$row['count'];
        }

        // Karyawan dengan akun aktif
        $result = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE status = 'active'");
        $stats['total_akun_aktif'] = $result->fetch_assoc()['total'];

        // Karyawan tanpa akun
        $result = $this->conn->query("SELECT COUNT(*) as total FROM karyawan WHERE id NOT IN (SELECT karyawan_id FROM users WHERE karyawan_id IS NOT NULL)");
        $stats['total_tanpa_akun'] = $result->fetch_assoc()['total'];

        // Karyawan bergabung bulan ini
        $result = $this->conn->query("SELECT COUNT(*) as total FROM karyawan WHERE MONTH(join_date) = MONTH(CURRENT_DATE()) AND YEAR(join_date) = YEAR(CURRENT_DATE())");
        $stats['bergabung_bulan_ini'] = $result->fetch_assoc()['total'];

        return $stats;
    }
}

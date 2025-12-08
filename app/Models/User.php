<?php
require_once __DIR__ . '/../Core/Database.php';

/**
 * User Model - Mengelola data user dan authentication
 * 
 * Fitur: authentication, user data, karyawan relation,
 * password management
 */
class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Get karyawan_id dari user berdasarkan user_id
     * 
     * @param int $userId
     * @return int|null
     */
    public function getKaryawanId($userId) {
        $stmt = $this->conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['karyawan_id'] ?? null;
    }

    /**
     * Find user berdasarkan email
     * 
     * @param string $email
     * @return array|null
     */
    public function findByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT id, email, password_hash, role, must_change_password, status, karyawan_id 
            FROM users 
            WHERE email = ?"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Find user berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, email, role, must_change_password, status, karyawan_id 
            FROM users 
            WHERE id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    /**
     * Update password user
     * 
     * @param int $userId
     * @param string $newPasswordHash
     * @return bool
     */
    public function updatePassword($userId, $newPasswordHash) {
        $stmt = $this->conn->prepare(
            "UPDATE users 
            SET password_hash = ?, 
                must_change_password = 0, 
                password_last_changed = NOW() 
            WHERE id = ?"
        );
        $stmt->bind_param('si', $newPasswordHash, $userId);
        
        return $stmt->execute();
    }

    /**
     * Verify user credentials
     * 
     * @param string $email
     * @param string $password
     * @return array|null User data jika berhasil, null jika gagal
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        // Remove password hash dari return value
        unset($user['password_hash']);
        
        return $user;
    }

    /**
     * Check apakah user aktif
     * 
     * @param array $user
     * @return bool
     */
    public function isActive($user) {
        return isset($user['status']) && $user['status'] === 'active';
    }

    /**
     * Check apakah user harus ganti password
     * 
     * @param array $user
     * @return bool
     */
    public function mustChangePassword($user) {
        return !empty($user['must_change_password']);
    }

    /**
     * Check apakah user adalah admin
     * 
     * @param array $user
     * @return bool
     */
    public function isAdmin($user) {
        return isset($user['role']) && in_array($user['role'], ['admin', 'super_admin']);
    }

    /**
     * Check apakah user adalah karyawan
     * 
     * @param array $user
     * @return bool
     */
    public function isKaryawan($user) {
        return isset($user['role']) && $user['role'] === 'karyawan';
    }
}

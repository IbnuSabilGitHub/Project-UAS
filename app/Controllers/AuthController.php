<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/PengajuanCuti.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Karyawan.php';

class AuthController extends BaseController
{
    private $db;
    private $modelLeave;
    private $modelAttendance;
    private $modelKaryawan;

    public function __construct()
    {
        $this->db = new Database();
        $this->modelLeave = new PengajuanCuti();
        $this->modelAttendance = new Attendance();
        $this->modelKaryawan = new Karyawan();
    }

    /**
     * Pastikan user adalah admin/super_admin
     */
    protected function ensureAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');
        }
    }

    /**
     * Pastikan user adalah karyawan
     */
    protected function ensureKaryawan()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');
        }
    }


    // Menampilkan halaman login
    public function loginPage()
    {
        // Start session di controller
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            // Jika sudah login, redirect ke dashboard sesuai role
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                redirect('/admin/dashboard');
            } else {
                redirect('/karyawan/dashboard');
            }
        }

        // Siapkan data untuk view
        $data = [
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'title' => 'Login - HRIS'
        ];

        unset($_SESSION['error'], $_SESSION['success']);

        $this->renderWithoutSidebar('auth/login', $data);
    }

    // Proses login
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        // Sanitas input username
        $username = trim($_POST['username'] ?? '');
        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
        $username = preg_replace('/[^a-zA-Z0-9_\-.@]/', '', $username);

        // password tidak disanitasi agar sesuai dengan hash asli
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username dan password harus diisi';
            redirect('/login');
        }

        // Konksi db
        $conn = $this->db->getConnection();

        // Query user berdasarkan username
        $stmt = $conn->prepare("SELECT id, username, password_hash, role, must_change_password, status FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $invalid_login = function () {
            $_SESSION['error'] = 'Username atau password salah';
            redirect('/login');
        };

        // Cek apakah user ditemukan atau tidak
        if ($result->num_rows === 0) {
            $invalid_login();
        }

        // Ambil data user dengan fetch_assoc
        $user = $result->fetch_assoc();


        // Cek status akun
        if ($user['status'] !== 'active') {
            $_SESSION['error'] = 'Akun dinonaktifkan';
            redirect('/login');
        }

        // Verifikasi password
        if (!password_verify($password, $user['password_hash'])) {
            $invalid_login();
        }

        session_regenerate_id(true); // cegah session fixation

        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = 'Login berhasil!';

        // Redirect jika wajib ganti password
        if (!empty($user['must_change_password'])) {
            redirect('/change-password');
        }
        // Redirect berdasarkan role
        if ($user['role'] === 'admin' || $user['role'] === 'super_admin') {
            redirect('/admin/dashboard');
        } else {
            redirect('/karyawan/dashboard');
        }
    }

    // Logout user
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Hapus semua data session
        session_destroy();
        redirect('/login');
    }

    // Legacy dashboard route: redirect ke role dashboard
    /**
     * Render halaman dashboard sesuai role
     */
    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            redirect('/admin/dashboard');
        } else {
            redirect('/karyawan/dashboard');
        }
    }

    /**
     * Render halaman dashboard admin
     */
    public function adminDashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->ensureAdmin();
        $statsLeave = $this->modelLeave->getStatistics();
        $statsAttendance = $this->modelAttendance->getAdminStats();
        $statsKaryawan = $this->modelKaryawan->getStatistics();
        

        $data = [
            'title' => 'Admin Dashboard',
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'success' => $_SESSION['success'] ?? null,
            'statsLeave' => $statsLeave,
            'statsAttendance' => $statsAttendance,
            'statsKaryawan' => $statsKaryawan
        ];
        unset($_SESSION['success']);
        $this->render('admin/dashboard', $data);
    }

    /**
     * Render halaman dashboard karyawan
     */
    public function employeeDashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->ensureKaryawan();

        // Ambil karyawan_id dari session user
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT karyawan_id FROM users WHERE id = ?");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $karyawanId = $user['karyawan_id'] ?? null;

        // Ambil statistik personal karyawan
        $statsLeave = [];
        $statsAttendance = [];
        
        if ($karyawanId) {
            $statsLeave = $this->modelLeave->getEmployeeStats($karyawanId);
            $statsAttendance = $this->modelAttendance->getEmployeeStats($karyawanId);
        }

        $data = [
            'title' => 'Karyawan Dashboard',
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'success' => $_SESSION['success'] ?? null,
            'statsLeave' => $statsLeave,
            'statsAttendance' => $statsAttendance
        ];
        unset($_SESSION['success']);
        $this->render('employee/dashboard', $data);
    }

    /**
     * Render halaman ganti password
     */
    public function changePasswordPage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $data = [
            'title' => 'Ganti Password',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ];

        // Bersihkan pesan session setelah diambil
        unset($_SESSION['error'], $_SESSION['success']);
        $this->render('auth/change_password', $data);
    }

    /**
     * Proses ganti password
     */
    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (strlen($new) < 8) {
            $_SESSION['error'] = 'Password minimal 8 karakter';
            redirect('/change-password');
        }
        if ($new !== $confirm) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok';
            redirect('/change-password');
        }
        $hash = password_hash($new, PASSWORD_BCRYPT);
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET password_hash = ?, must_change_password = 0, password_last_changed = NOW() WHERE id = ?");
        $stmt->bind_param('si', $hash, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password berhasil diubah';
            redirect('/dashboard');
        } else {
            $_SESSION['error'] = 'Gagal mengubah password';
            redirect('/change-password');
        }
    }
}

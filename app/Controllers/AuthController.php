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
            setFlash('error', 'Akses ditolak');
            redirect('/admin/login');
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
            setFlash('error', 'Akses ditolak');
            redirect('/karyawan/login');
        }
    }

    /**
     * Halaman index - pilihan login
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Jika sudah login, redirect ke dashboard sesuai role
        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                redirect('/admin/dashboard');
            } else {
                redirect('/karyawan/dashboard');
            }
        }

        $data = [
            'title' => 'HRIS - Pilih Login'
        ];

        $this->renderWithoutSidebar('index', $data);
    }

    /**
     * Halaman login admin
     */
    public function adminLoginPage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                redirect('/admin/dashboard');
            } else {
                redirect('/karyawan/dashboard');
            }
        }

        $data = [
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'title' => 'Login Admin - HRIS'
        ];

        unset($_SESSION['error'], $_SESSION['success']);

        $this->renderWithoutSidebar('auth/login-admin', $data);
    }

    /**
     * Halaman login karyawan
     */
    public function karyawanLoginPage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                redirect('/admin/dashboard');
            } else {
                redirect('/karyawan/dashboard');
            }
        }

        $data = [
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'title' => 'Login Karyawan - HRIS'
        ];

        unset($_SESSION['error'], $_SESSION['success']);

        $this->renderWithoutSidebar('auth/login-karyawan', $data);
    }

    /**
     * Proses login admin
     */
    public function adminLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password harus diisi');
            redirect('/admin/login');
        }

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, email, password_hash, role, must_change_password, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            setFlash('error', 'Email atau password salah');
            redirect('/admin/login');
        }

        $user = $result->fetch_assoc();

        // Cek apakah user adalah admin/super_admin
        if (!in_array($user['role'], ['admin', 'super_admin'])) {
            setFlash('error', 'Anda tidak memiliki akses admin');
            redirect('/admin/login');
        }

        if ($user['status'] !== 'active') {
            setFlash('error', 'Akun dinonaktifkan');
            redirect('/admin/login');
        }

        if (!password_verify($password, $user['password_hash'])) {
            setFlash('error', 'Email atau password salah');
            redirect('/admin/login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        setFlash('success', 'Login berhasil!');

        if (!empty($user['must_change_password'])) {
            redirect('/change-password');
        }

        redirect('/admin/dashboard');
    }

    /**
     * Proses login karyawan
     */
    public function karyawanLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/karyawan/login');
        }

        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password harus diisi');
            redirect('/karyawan/login');
        }

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, email, password_hash, role, must_change_password, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            setFlash('error', 'Email atau password salah');
            redirect('/karyawan/login');
        }

        $user = $result->fetch_assoc();

        // Cek apakah user adalah karyawan
        if ($user['role'] !== 'karyawan') {
            setFlash('error', 'Gunakan halaman login admin untuk akun administrator');
            redirect('/karyawan/login');
        }

        if ($user['status'] !== 'active') {
            setFlash('error', 'Akun dinonaktifkan');
            redirect('/karyawan/login');
        }

        if (!password_verify($password, $user['password_hash'])) {
            setFlash('error', 'Email atau password salah');
            redirect('/karyawan/login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        setFlash('success', 'Login berhasil!');

        if (!empty($user['must_change_password'])) {
            redirect('/change-password');
        }

        redirect('/karyawan/dashboard');
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
            redirect('/login'); //backward compatibility
        }

        // Sanitas input email
        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // password tidak disanitasi agar sesuai dengan hash asli
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email dan password harus diisi';
            redirect('/login');//backward compatibility
        }

        // Konksi db
        $conn = $this->db->getConnection();

        // Query user berdasarkan email
        $stmt = $conn->prepare("SELECT id, email, password_hash, role, must_change_password, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $invalid_login = function () {
            $_SESSION['error'] = 'Email atau password salah';
            redirect('/login');//backward compatibility
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
            redirect('/login');//backward compatibility
        }

        // Verifikasi password
        if (!password_verify($password, $user['password_hash'])) {
            $invalid_login();
        }

        session_regenerate_id(true); // cegah session fixation

        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
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
        redirect('/login');//backward compatibility
    }

    public function indexPage()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        } else {
            $this->render('/', ['title' => 'HRIS']);
        }
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
            redirect('/login');//backward compatibility
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
        $allStats = $this->modelKaryawan->getStatistics();
        
        // Format statsKaryawan untuk kompatibilitas dengan view
        $statsKaryawan = [
            'total' => $allStats['total_karyawan'] ?? 0,
            'active' => $allStats['by_status']['active'] ?? 0,
            'inactive' => $allStats['by_status']['inactive'] ?? 0,
            'resigned' => 0, // Data dari employment_status
            'new_this_month' => $allStats['bergabung_bulan_ini'] ?? 0,
            'by_position' => $allStats['by_position'] ?? []
        ];

        $data = [
            'title' => 'Admin Dashboard',
            'email' => $_SESSION['email'],
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
            'email' => $_SESSION['email'],
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
            redirect('/login');//backward compatibility
        }
        $data = [
            'title' => 'Ganti Password',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ];

        // Bersihkan pesan session setelah diambil
        unset($_SESSION['error'], $_SESSION['success']);
        $this->renderWithoutSidebar('auth/change-password', $data);

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
            redirect('/login');//backward compatibility
        }

        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (strlen($new) < 8) {
            setFlash('error', 'Password minimal 8 karakter');
            redirect('/change-password');
        }

        if ($new !== $confirm) {
            setFlash('error', 'Konfirmasi password tidak cocok');
            redirect('/change-password');
        }

        $hash = password_hash($new, PASSWORD_BCRYPT);
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET password_hash = ?, must_change_password = 0, password_last_changed = NOW() WHERE id = ?");
        $stmt->bind_param('si', $hash, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            setFlash('success', 'Password berhasil diubah');
            redirect('/dashboard');
        } else {
            setFlash('error', 'Gagal mengubah password');
            redirect('/change-password');
        }
    }
}

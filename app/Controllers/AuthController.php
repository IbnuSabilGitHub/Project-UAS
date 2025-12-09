<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/PengajuanCuti.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Karyawan.php';

/**
 * AuthController - Mengelola authentication dan authorization
 * 
 * Fitur: login (admin dan karyawan), logout, change password,
 * dashboard routing berdasarkan role
 */
class AuthController extends BaseController
{
    private $db;
    private $modelLeave;
    private $modelAttendance;
    private $modelKaryawan;

    public function __construct()
    {
        parent::__construct(); // Initialize userModel dari BaseController
        $this->db = new Database();
        $this->modelLeave = new PengajuanCuti();
        $this->modelAttendance = new Attendance();
        $this->modelKaryawan = new Karyawan();
    }

    /**
     * Halaman index - pilihan login
     * 
     * @return void
     */
    public function index()
    {
        $this->redirectIfAuthenticated();

        $data = [
            'title' => 'HRIS - Pilih Login'
        ];

        $this->renderWithoutSidebar('index', $data);
    }

    /**
     * Halaman login admin
     * 
     * @return void
     */
    public function adminLoginPage()
    {
        $this->redirectIfAuthenticated();

        $data = [
            'title' => 'Login Admin - HRIS'
        ];

        $this->renderWithoutSidebar('auth/login-admin', $data);
    }

    /**
     * Halaman login karyawan
     * 
     * @return void
     */
    public function karyawanLoginPage()
    {
        $this->redirectIfAuthenticated();

        $data = [
            'title' => 'Login Karyawan - HRIS'
        ];

        $this->renderWithoutSidebar('auth/login-karyawan', $data);
    }

    /**
     * Proses login admin
     * 
     * @return void
     */
    public function adminLogin()
    {
        $this->startSession();
        $this->validateMethod('POST', '/admin/login');

        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password harus diisi');
            redirect('/admin/login');
        }

        // Gunakan User Model untuk authentication
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            setFlash('error', 'Email atau password salah');
            redirect('/admin/login');
        }

        // Cek apakah user adalah admin/super_admin
        if (!$this->userModel->isAdmin($user)) {
            setFlash('error', 'Anda tidak memiliki akses admin');
            redirect('/admin/login');
        }

        if (!$this->userModel->isActive($user)) {
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

        redirect('/admin/dashboard');
    }

    /**
     * Proses login karyawan
     */
    public function karyawanLogin()
    {
        $this->startSession();
        $this->validateMethod('POST', '/karyawan/login');

        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('error', 'Email dan password harus diisi');
            redirect('/karyawan/login');
        }

        // Gunakan User Model untuk authentication (MVC compliant)
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            setFlash('error', 'Email atau password salah');
            redirect('/karyawan/login');
        }

        // Cek apakah user adalah karyawan
        if (!$this->userModel->isKaryawan($user)) {
            setFlash('error', 'Gunakan halaman login admin untuk akun administrator');
            redirect('/karyawan/login');
        }

        // Cek status akun
        if (!$this->userModel->isActive($user)) {
            setFlash('error', 'Akun dinonaktifkan');
            redirect('/karyawan/login');
        }

        // Verifikasi password
        if (!password_verify($password, $user['password_hash'])) {
            setFlash('error', 'Email atau password salah');
            redirect('/karyawan/login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['must_change_password'] = $this->userModel->mustChangePassword($user);
        setFlash('success', 'Login berhasil!');

        redirect('/karyawan/dashboard');
    }

    /**
     * Proses logout
     * 
     * @return void
     */
    public function logout()
    {
        $this->startSession();
        // Hapus semua data session
        session_destroy();
        redirect('/');//backward compatibility
    }

    /**
     * Halaman utama aplikasi
     * 
     * @return void
     */
    public function indexPage()
    {
        $this->startSession();

        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        } else {
            $this->render('/', ['title' => 'HRIS']);
        }
    }

    // Legacy dashboard route: redirect ke role dashboard
    /**
     * Render halaman dashboard sesuai role
     * 
     * @return void
     */
    public function dashboard()
    {
        $this->ensureAuthenticated();

        if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            redirect('/admin/dashboard');
        } else {
            redirect('/karyawan/dashboard');
        }
    }

    /**
     * Render halaman dashboard admin
     * 
     * @return void
     */
    public function adminDashboard()
    {
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
            'statsLeave' => $statsLeave,
            'statsAttendance' => $statsAttendance,
            'statsKaryawan' => $statsKaryawan
        ];
        $this->render('admin/dashboard', $data);
    }

    /**
     * Render halaman dashboard karyawan
     */
    public function employeeDashboard()
    {
        $this->ensureKaryawan();

        // Gunakan helper method dari BaseController (MVC compliant)
        $karyawanId = $this->getKaryawanId();

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
            'success' => $this->getFlash('success'),
            'statsLeave' => $statsLeave,
            'statsAttendance' => $statsAttendance
        ];
        
        $this->render('employee/dashboard', $data);
    }

    /**
     * Render halaman ganti password
     */
    public function changePasswordPage()
    {
        $this->ensureAuthenticated();

        $data = [
            'title' => 'Ganti Password'
        ];

        $this->renderWithoutSidebar('auth/change-password', $data);
    }

    /**
     * Proses ganti password
     */
    public function changePassword()
    {
        $this->ensureAuthenticated();
        $this->validateMethod('POST', '/change-password');
        
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
        
        // Gunakan User Model untuk update password (MVC compliant)
        if ($this->userModel->updatePassword($_SESSION['user_id'], $hash)) {
            $_SESSION['must_change_password'] = false;
            setFlash('success', 'Password berhasil diubah');
            redirect('/dashboard');
        } else {
            setFlash('error', 'Gagal mengubah password');
            redirect('/change-password');
        }
    }
}

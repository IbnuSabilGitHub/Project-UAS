<?php
require_once __DIR__ . '/../Core/Database.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Render halaman view
    private function render($view, $data = []) {
        extract($data); // Konversi array data menjadi variabel
        require __DIR__ . "/../Views/{$view}.php";
    }

    
    // Menampilkan halaman login
    public function loginPage() {
        // Start session di controller
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Siapkan data untuk view
        $data = [
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'title' => 'Login - HRIS'
        ];

        unset($_SESSION['error'], $_SESSION['success']);

        $this->render('auth/login', $data);
    }

    // Proses logi
    public function login() {
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
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
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

    
        // Verifikasi password
        if (!password_verify($password, $user['password_hash'])) {
            $invalid_login();
        }

        session_regenerate_id(true); // cegah session fixation

        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = 'Login berhasil!';

        // Redirect ke dashboard
        redirect('/dashboard');
    }

    // Logout user
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Hapus semua data session
        session_destroy();
        redirect('/login');
    }

    // Menampilkan dashboard
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Cek apakah user sudah login, jika belum redirect ke login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        // Siapkan data untuk view
        $data = [
            'title' => 'Dashboard - HRIS',
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'success' => $_SESSION['success'] ?? null
        ];

        unset($_SESSION['success']);

        $this->render('dashboard/index', $data);
    }
}
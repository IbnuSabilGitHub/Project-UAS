<?php
require_once __DIR__ . '/../Core/Database.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Render view dengan data
    private function render($view, $data = []) {
        extract($data); // Convert array keys to variables
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
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username dan password harus diisi';
            header('Location: /login');
            exit;
        }

        // Query untuk mencari user
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $_SESSION['error'] = 'Username atau password salah';
            header('Location: /login');
            exit;
        }

        $user = $result->fetch_assoc();

        if ($password !== $user['password_hash']) {
            $_SESSION['error'] = 'Username atau password salah';
            header('Location: /login');
            exit;
        }

        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = 'Login berhasil!';

        // Redirect ke dashboard
        header('Location: /dashboard');
        exit;
    }

    // Logout user
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /login');
        exit;
    }

    // Menampilkan dashboard
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
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
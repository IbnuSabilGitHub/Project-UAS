<?php
require_once __DIR__ . '/../Models/User.php';

/**
 * BaseController - Base class untuk semua controllers
 * 
 * Menyediakan helper methods untuk authentication, authorization,
 * dan rendering views
 */
class BaseController {
    protected $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Start session jika belum dimulai
     * 
     * @return void
     */
    protected function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Pastikan user sudah login
     * 
     * @return void
     */
    protected function ensureAuthenticated() {
        $this->startSession();
        
        if (!isset($_SESSION['user_id'])) {
            setFlash('error', 'Silakan login terlebih dahulu');
            redirect('/');
        }
    }

    /**
     * Pastikan user adalah admin/super_admin
     * 
     * @return void
     */
    protected function ensureAdmin() {
        $this->startSession();
        
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            setFlash('error', 'Akses ditolak');
            redirect('/admin/login');
        }
    }

    /**
     * Pastikan user adalah karyawan
     * 
     * @return void
     */
    protected function ensureKaryawan() {
        $this->startSession();
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
            setFlash('error', 'Akses ditolak');
            redirect('/karyawan/login');
        }
    }
    
    /**
     * Get karyawan_id dari user yang sedang login
     * 
     * @return int|null
     */
    protected function getKaryawanId() {
        $this->startSession();
        
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        return $this->userModel->getKaryawanId($_SESSION['user_id']);
    }
    
    /**
     * Redirect jika sudah login
     * 
     * @return void
     */
    protected function redirectIfAuthenticated() {
        $this->startSession();
        
        if (isset($_SESSION['user_id'])) {
            if (in_array($_SESSION['role'], ['admin', 'super_admin'])) {
                redirect('/admin/dashboard');
            } else {
                redirect('/karyawan/dashboard');
            }
        }
    }
    
    /**
     * Validate request method
     * 
     * @param string $method Expected HTTP method
     * @param string $redirectTo Redirect URL jika method tidak sesuai
     * @return void
     */
    protected function validateMethod($method, $redirectTo) {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            redirect($redirectTo);
        }
    }


    /**
     * Render view dengan layout lengkap (header, sidebar, footer)
     * 
     * @param string $view Path ke view file (relatif dari Views/)
     * @param array $data Data yang akan dikirim ke view
     * @param bool $withSidebar Apakah menggunakan sidebar (default: true)
     * @return void
     */
    protected function render($view, $data = [], $withSidebar = true) {
        $this->startSession();

        // Extract data untuk view
        extract($data);

        // Set default values jika tidak ada di data
        $email = $email ?? ($_SESSION['email'] ?? null);
        $role = $role ?? ($_SESSION['role'] ?? null);

        require_once __DIR__ . '/../Views/layouts/header.php';

        // Include sidebar jika diperlukan dan user sudah login
        if ($withSidebar && isset($_SESSION['role']) && isset($_SESSION['email'])) {
            $this->renderSidebar($role, $email);
        }

        require_once __DIR__ . "/../Views/{$view}.php";
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
    
    /**
     * Render view tanpa sidebar (untuk halaman login, dll)
     * 
     * @param string $view Path ke view file (relatif dari Views/)
     * @param array $data Data yang akan dikirim ke view
     * @return void
     */
    protected function renderWithoutSidebar($view, $data = []) {
        $this->render($view, $data, false);
    }

    /**
     * Render sidebar berdasarkan role user
     * 
     * @param string $role Role user
     * @param string $email User email
     * @return void
     */
    private function renderSidebar($role, $email) {
        if (in_array($role, ['admin', 'super_admin'])) {
            // Get pending leave requests count for admin sidebar
            $pendingCount = 0;
            try {
                require_once __DIR__ . '/../Models/PengajuanCuti.php';
                $cutiModel = new PengajuanCuti();
                $pendingCount = $cutiModel->countPending();
            } catch (Exception $e) {
                // Fallback to 0 if there's an error
                $pendingCount = 0;
            }
            
            require_once __DIR__ . '/../Views/layouts/sidebar-admin.php';
        } elseif ($role === 'karyawan') {
            require_once __DIR__ . '/../Views/layouts/sidebar-karyawan.php';
        }
    }
    
    /**
     * Get flash message dan hapus dari session
     * 
     * @param string $key Flash message key
     * @return string|null
     */
    protected function getFlash($key) {
        $this->startSession();
        
        $message = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        
        return $message;
    }
    
    /**
     * Clear multiple flash messages
     * 
     * @param array $keys Array dari keys yang ingin dihapus
     * @return void
     */
    protected function clearFlash($keys = ['success', 'error', 'warning', 'info']) {
        $this->startSession();
        
        foreach ($keys as $key) {
            unset($_SESSION[$key]);
        }
    }
}
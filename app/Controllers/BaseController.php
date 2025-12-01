<?php

class BaseController {
    /**
     * Pastikan user sudah login
     */
    protected function ensureAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu';
            redirect('/login');//backward compatibility
        }
    }

    /**
     * Pastikan user adalah admin/super_admin
     */
    protected function ensureAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');//backward compatibility
        }
    }

    /**
     * Pastikan user adalah karyawan
     */
    protected function ensureKaryawan() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
            $_SESSION['error'] = 'Akses ditolak';
            redirect('/login');//backward compatibility
        }
    }


    /**
     * Render view dengan layout yang lengkap (header, sidebar, footer)
     * 
     * @param string $view Path ke view file
     * @param array $data Data yang akan dikirim ke view
     * @param bool $withSidebar Apakah menggunakan sidebar (default: true)
     */
    public function render($view, $data = [], $withSidebar = true) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Extract data untuk view
        extract($data);

        // Set default values jika tidak ada di data
        $email = $email ?? ($_SESSION['email'] ?? null);
        $role = $role ?? ($_SESSION['role'] ?? null);

        // Include header
        require_once __DIR__ . '/../Views/layouts/header.php';

        // Include sidebar jika diperlukan dan user sudah login
        if ($withSidebar && isset($_SESSION['role']) && isset($_SESSION['email'])) {
            $this->renderSidebar($role, $email);
        }

        // Include main view
        require_once __DIR__ . "/../Views/{$view}.php";

        // Include footer
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Render sidebar berdasarkan role user
     * 
     * @param string $role Role user
     * @param string $email User email
     */
    private function renderSidebar($role, $email) {
        if (in_array($role, ['admin', 'super_admin'])) {
            require_once __DIR__ . '/../Views/layouts/sidebar-admin.php';
        } elseif ($role === 'karyawan') {
            require_once __DIR__ . '/../Views/layouts/sidebar-karyawan.php';
        }
    }

    /**
     * Render view tanpa sidebar (untuk halaman login, error)
     * 
     * @param string $view Path ke view file
     * @param array $data Data yang akan dikirim ke view
     */
    public function renderWithoutSidebar($view, $data = []) {
        $this->render($view, $data, false);
    }


}
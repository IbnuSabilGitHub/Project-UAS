<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Karyawan.php';

class KaryawanController extends BaseController {
    
    private $model;

    public function __construct() {
        $this->model = new Karyawan();
    }


    /**
     * Menampilkan daftar karyawan
     */
    public function index() {
        $this->ensureAdmin();
        
        // Dapatkan filter dari query parameter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : [];
        
        // dapatkan semua karyawan
        $karyawans = $this->model->allWithUser();
        
        // Terapkan filter pencarian (berdasarkan nama atau NIK)
        if (!empty($search)) {
            $karyawans = array_filter($karyawans, function($k) use ($search) {
                return stripos($k['name'], $search) !== false || 
                       stripos($k['nik'], $search) !== false;
            });
        }
        
        // Terapkan filter status employment
        if (!empty($statusFilter) && is_array($statusFilter)) {
            $karyawans = array_filter($karyawans, function($k) use ($statusFilter) {
                $empStatus = $k['employment_status'] ?? 'active';
                return in_array($empStatus, $statusFilter);
            });
        }
        
        // Hitung statistik
        $allEmployees = $this->model->allWithUser();
        $statistics = [
            'total' => count($allEmployees),
            'active' => count(array_filter($allEmployees, fn($k) => ($k['employment_status'] ?? 'active') === 'active')),
            'on_leave' => count(array_filter($allEmployees, fn($k) => ($k['employment_status'] ?? 'active') === 'on_leave')),
            'resigned' => count(array_filter($allEmployees, fn($k) => ($k['employment_status'] ?? 'active') === 'resigned')),
        ];
        
        $this->render('admin/employees/index', [
            'title' => 'List Karyawan', 
            'karyawans' => $karyawans,
            'statistics' => $statistics,
            'currentSearch' => $search,
            'currentStatus' => $statusFilter
        ]);
    }

    /**
     * Menampilkan form tambah karyawan
     */
    public function create() {
        $this->ensureAdmin();
        $this->render('admin/employees/form', ['title' => 'Tambah Karyawan', 'karyawan' => null]);
    }

    /**
     * Membuat akun karyawan baru
     */
    public function create_account() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }

        $data = [
            'nik' => substr(trim($_POST['nik'] ?? ''), 0, 50),
            'name' => substr(trim($_POST['name'] ?? ''), 0, 191),
            'email' => substr(trim($_POST['email'] ?? ''), 0, 191),
            'phone' => substr(trim($_POST['phone'] ?? ''), 0, 50),
            'position' => substr(trim($_POST['position'] ?? ''), 0, 100),
            'join_date' => $_POST['join_date'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Validasi data yang diperlukan
        if (empty($data['nik']) || empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'NIK, Nama, dan Email wajib diisi';
            redirect('/admin/karyawan');
        }

        $insertId = $this->model->create($data);
        if ($insertId) {
            if (!empty($_POST['create_account'])) {
                $email = $data['email'];
                $tempPassword = $this->generateTempPassword();
                if ($this->model->createAccount($insertId, $email, $tempPassword)) {
                    $_SESSION['success'] = 'Karyawan & akun dibuat. Email: ' . htmlspecialchars($email) . ' | Temp Password: ' . htmlspecialchars($tempPassword);
                } else {
                    $_SESSION['success'] = 'Karyawan dibuat (akun sudah ada sebelumnya)';
                }
            } else {
                $_SESSION['success'] = 'Karyawan berhasil dibuat';
            }
        } else {
            $error = $this->model->getLastError();
            $_SESSION['error'] = 'Gagal menyimpan data karyawan' . ($error ? ': ' . $error : '');
        }

        redirect('/admin/karyawan');
    }

    /**
     * Menampilkan form edit karyawan sesuai ID dari query parameter
     */
    public function edit() {
        $this->ensureAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            redirect('/admin/karyawan');
        }
        $karyawan = $this->model->find($id);
        if (!$karyawan) {
            $_SESSION['error'] = 'Karyawan tidak ditemukan';
            redirect('/admin/karyawan');
        }
        $this->render('admin/employees/form', ['title' => 'Edit Karyawan', 'karyawan' => $karyawan]);
    }

    /**
     * Memperbarui data karyawan sesuai ID
     */
    public function update() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid';
            redirect('/admin/karyawan');
        }

        $data = [
            'nik' => substr(trim($_POST['nik'] ?? ''), 0, 50),
            'name' => substr(trim($_POST['name'] ?? ''), 0, 191),
            'email' => substr(trim($_POST['email'] ?? ''), 0, 191),
            'phone' => substr(trim($_POST['phone'] ?? ''), 0, 50),
            'position' => substr(trim($_POST['position'] ?? ''), 0, 100),
            'join_date' => $_POST['join_date'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Validasi data yang diperlukan
        if (empty($data['nik']) || empty($data['name']) || empty($data['email'])) {
            $_SESSION['error'] = 'NIK, Nama, dan Email wajib diisi';
            redirect('/admin/karyawan');
        }

        if ($this->model->update($id, $data)) {
            $_SESSION['success'] = 'Data karyawan diperbarui';
        } else {
            $error = $this->model->getLastError();
            $_SESSION['error'] = 'Gagal memperbarui data' . ($error ? ': ' . $error : '');
        }
        redirect('/admin/karyawan');
    }

    /**
     * Menghapus data karyawan sesuai ID
     */
    public function delete() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid';
            redirect('/admin/karyawan');
        }
        // Hanya super_admin boleh hard delete
        if (($_SESSION['role'] ?? '') !== 'super_admin') {
            $_SESSION['error'] = 'Hanya super admin boleh hapus permanen';
            redirect('/admin/karyawan');
        }
        if ($this->model->deleteWithUser($id)) {
            $_SESSION['success'] = 'Data karyawan & akun dihapus permanen';
        } else {
            $_SESSION['error'] = 'Gagal hapus permanen';
        }
        redirect('/admin/karyawan');
    }

    /**
     * Mengaktifkan akun karyawan sesuai ID dari form
     */
    public function activateAccount() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
        $karyawanId = isset($_POST['karyawan_id']) ? (int)$_POST['karyawan_id'] : 0;
        if (!$karyawanId) {
            $_SESSION['error'] = 'ID karyawan tidak valid';
            redirect('/admin/karyawan');
        }
        $karyawan = $this->model->find($karyawanId);
        if (!$karyawan) {
            $_SESSION['error'] = 'Karyawan tidak ditemukan';
            redirect('/admin/karyawan');
        }
        if ($this->model->getUserByKaryawanId($karyawanId)) {
            $_SESSION['error'] = 'Karyawan sudah memiliki akun';
            redirect('/admin/karyawan');
        }
        $email = $karyawan['email'];
        $tempPassword = $this->generateTempPassword();
        if ($this->model->createAccount($karyawanId, $email, $tempPassword)) {
            $_SESSION['success'] = 'Akun diaktifkan. Email: ' . htmlspecialchars($email) . ' | Temp Password: ' . htmlspecialchars($tempPassword);
        } else {
            $_SESSION['error'] = 'Gagal mengaktifkan akun';
        }
        redirect('/admin/karyawan');
    }

    /** Nonaktifkan karyawan */
    public function deactivate() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid';
            redirect('/admin/karyawan');
        }
        if ($this->model->deactivate($id)) {
            $_SESSION['success'] = 'Karyawan dinonaktifkan';
        } else {
            $_SESSION['error'] = 'Gagal menonaktifkan karyawan';
        }
        redirect('/admin/karyawan');
    }

    /**
     * Menghasilkan password sementara acak
     * 
     * @param int $length
     * @return string
     */
    private function generateTempPassword() {
        $pw = bin2hex(random_bytes(5)); // 10 karakter random
        return $pw;
    }
}

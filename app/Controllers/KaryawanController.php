<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Karyawan.php';

/**
 * KaryawanController - Mengelola CRUD karyawan (Admin only)
 * 
 * Fitur: list karyawan, create, edit, delete, activate/deactivate account,
 * filter & search, statistik karyawan
 */
class KaryawanController extends BaseController {
    
    private $model;

    public function __construct() {
        parent::__construct(); // Initialize userModel
        $this->model = new Karyawan();

    }


    /**
     * Menampilkan daftar karyawan
     * 
     * @return void
     */
    public function index() {
        $this->ensureAdmin();
        
        // Dapatkan filter dari query parameter
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : [];
        $positionFilter = isset($_GET['position']) ? $_GET['position'] : [];
        
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
        
        // Terapkan filter posisi
        if (!empty($positionFilter) && is_array($positionFilter)) {
            $karyawans = array_filter($karyawans, function($k) use ($positionFilter) {
                return in_array($k['position'], $positionFilter);
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
            'currentStatus' => $statusFilter,
            'currentPosition' => $positionFilter,
            'availablePositions' => Karyawan::getAvailablePositions()
        ]);
    }

    /**
     * Menampilkan form tambah karyawan
     * 
     * @return void
     */
    public function create() {
        $this->ensureAdmin();
        $this->render('admin/employees/form', [
            'title' => 'Tambah Karyawan', 
            'karyawan' => null,
            'availablePositions' => Karyawan::getAvailablePositions()
        ]);
    }

    /**
     * Membuat akun karyawan baru
     * 
     * @return void
     */
    public function create_account() {
        $this->ensureAdmin();
        $this->validateMethod('POST', '/admin/karyawan');

        $data = [
            'nik' => substr(trim($_POST['nik'] ?? ''), 0, 50),
            'name' => substr(trim($_POST['name'] ?? ''), 0, 191),
            'email' => substr(trim($_POST['email'] ?? ''), 0, 191),
            'phone' => substr(trim($_POST['phone'] ?? ''), 0, 50),
            'position' => trim($_POST['position'] ?? ''),
            'join_date' => $_POST['join_date'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Validasi data yang diperlukan
        if (empty($data['nik']) || empty($data['name']) || empty($data['email'])) {
            setFlash('error', 'NIK, Nama, dan Email wajib diisi');
            redirect('/admin/karyawan');
        }

        // Validasi NIK harus 16 digit
        if (!preg_match('/^[0-9]{16}$/', $data['nik'])) {
            setFlash('error', 'NIK harus tepat 16 digit angka');
            redirect('/admin/karyawan');
        }

        // Validasi position harus sesuai ENUM
        if (empty($data['position']) || !Karyawan::isValidPosition($data['position'])) {
            setFlash('error', 'Posisi tidak valid. Pilih salah satu posisi yang tersedia');
            redirect('/admin/karyawan');
        }

        // Validasi tanggal join tidak boleh masa depan
        if (!empty($data['join_date']) && strtotime($data['join_date']) > time()) {
            setFlash('error', 'Tanggal masuk tidak boleh di masa depan');
            redirect('/admin/karyawan');
        }

        $insertId = $this->model->create($data);
        if ($insertId) {
            if (!empty($_POST['create_account'])) {
                $email = $data['email'];
                $tempPassword = $this->generateTempPassword();
                if ($this->model->createAccount($insertId, $email, $tempPassword)) {
                    // Simpan ke session untuk ditampilkan di modal
                    $_SESSION['temp_password_data'] = [
                        'email' => $email,
                        'password' => $tempPassword
                    ];
                } else {
                    setFlash('success', 'Karyawan dibuat (akun sudah ada sebelumnya)');
                }
            } else {
                setFlash('success', 'Karyawan berhasil dibuat');
            }
        } else {
            $error = $this->model->getLastError();
            setFlash('error', 'Gagal menyimpan data karyawan' . ($error ? ': ' . $error : ''));
        }

        redirect('/admin/karyawan');
    }

    /**
     * Menampilkan form edit karyawan sesuai ID dari query parameter
     * 
     * @return void
     */
    public function edit() {
        $this->ensureAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            redirect('/admin/karyawan');
        }
        $karyawan = $this->model->find($id);
        if (!$karyawan) {
            setFlash('error', 'Karyawan tidak ditemukan');
            redirect('/admin/karyawan');
        }
        $this->render('admin/employees/form', [
            'title' => 'Edit Karyawan', 
            'karyawan' => $karyawan,
            'availablePositions' => Karyawan::getAvailablePositions()
        ]);
    }

    /**
     * Memperbarui data karyawan sesuai ID
     * 
     * @return void
     */
    public function update() {
        $this->ensureAdmin();
        $this->validateMethod('POST', '/admin/karyawan');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            setFlash('error', 'ID tidak valid');
            redirect('/admin/karyawan');
        }

        $data = [
            'nik' => substr(trim($_POST['nik'] ?? ''), 0, 50),
            'name' => substr(trim($_POST['name'] ?? ''), 0, 191),
            'email' => substr(trim($_POST['email'] ?? ''), 0, 191),
            'phone' => substr(trim($_POST['phone'] ?? ''), 0, 50),
            'position' => trim($_POST['position'] ?? ''),
            'join_date' => $_POST['join_date'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];

        // Validasi data yang diperlukan
        if (empty($data['nik']) || empty($data['name']) || empty($data['email'])) {
            setFlash('error', 'NIK, Nama, dan Email wajib diisi');
            redirect('/admin/karyawan');
        }

        // Validasi NIK harus 16 digit
        if (!preg_match('/^[0-9]{16}$/', $data['nik'])) {
            setFlash('error', 'NIK harus tepat 16 digit angka');
            redirect('/admin/karyawan');
        }

        // Validasi position harus sesuai ENUM
        if (empty($data['position']) || !Karyawan::isValidPosition($data['position'])) {
            setFlash('error', 'Posisi tidak valid. Pilih salah satu posisi yang tersedia');
            redirect('/admin/karyawan');
        }

        // Validasi tanggal join tidak boleh masa depan
        if (!empty($data['join_date']) && strtotime($data['join_date']) > time()) {
            setFlash('error', 'Tanggal masuk tidak boleh di masa depan');
            redirect('/admin/karyawan');
        }

        if ($this->model->update($id, $data)) {
            setFlash('success', 'Data karyawan diperbarui');
        } else {
            $error = $this->model->getLastError();
            setFlash('error', 'Gagal memperbarui data' . ($error ? ': ' . $error : ''));
        }
        redirect('/admin/karyawan');
    }

    /**
     * Menghapus data karyawan sesuai ID
     * 
     * @return void
     */
    public function delete() {
        $this->ensureAdmin();
        $this->validateMethod('POST', '/admin/karyawan');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            setFlash('error', 'ID tidak valid');
            redirect('/admin/karyawan');
        }
        // Hanya super_admin boleh hard delete
        if (($_SESSION['role'] ?? '') !== 'super_admin') {
            setFlash('error', 'Hanya super admin boleh hapus permanen');
            redirect('/admin/karyawan');
        }
        if ($this->model->deleteWithUser($id)) {
            setFlash('success', 'Data karyawan & akun dihapus permanen');
        } else {
            setFlash('error', 'Gagal hapus permanen');
        }
        redirect('/admin/karyawan');
    }

    /**
     * Mengaktifkan akun karyawan sesuai ID dari form
     * 
     * @return void
     */
    public function activateAccount() {
        $this->ensureAdmin();
        $this->validateMethod('POST', '/admin/karyawan');
        $karyawanId = isset($_POST['karyawan_id']) ? (int)$_POST['karyawan_id'] : 0;
        if (!$karyawanId) {
            setFlash('error', 'ID karyawan tidak valid');
            redirect('/admin/karyawan');
        }
        $karyawan = $this->model->find($karyawanId);
        if (!$karyawan) {
            setFlash('error', 'Karyawan tidak ditemukan');
            redirect('/admin/karyawan');
        }
        if ($this->model->getUserByKaryawanId($karyawanId)) {
            setFlash('error', 'Karyawan sudah memiliki akun');
            redirect('/admin/karyawan');
        }
        $email = $karyawan['email'];
        $tempPassword = $this->generateTempPassword();
        if ($this->model->createAccount($karyawanId, $email, $tempPassword)) {
            // Simpan ke session untuk ditampilkan di modal
            $_SESSION['temp_password_data'] = [
                'email' => $email,
                'password' => $tempPassword
            ];
        } else {
            setFlash('error', 'Gagal mengaktifkan akun');
        }
        redirect('/admin/karyawan');
    }

    /** 
     * Nonaktifkan karyawan 
     * 
     * @return void
     */
    public function deactivate() {
        $this->ensureAdmin();
        $this->validateMethod('POST', '/admin/karyawan');
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if (!$id) {
            setFlash('error', 'ID tidak valid');
            redirect('/admin/karyawan');
        }
        if ($this->model->deactivate($id)) {
            setFlash('success', 'Karyawan dinonaktifkan');
        } else {
            setFlash('error', 'Gagal menonaktifkan karyawan');
        }
        redirect('/admin/karyawan');
    }

    /**
     * Menghasilkan password sementara acak
     * 
     * Helper method untuk generate temporary password saat membuat akun karyawan.
     * Berada di controller karena logic ini spesifik untuk business flow pembuatan akun.
     * Password ini akan ditampilkan ke admin untuk diberikan ke karyawan.
     * 
     * @return string Random password (10 karakter hexadecimal)
     */
    private function generateTempPassword() {
        return bin2hex(random_bytes(5)); // 10 karakter random
    }
}

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
        $karyawans = $this->model->allWithUser();
        $this->render('admin/employees/index', ['title' => 'List Karyawan', 'karyawans' => $karyawans]);
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
            setFlash('error', 'NIK, Nama, dan Email wajib diisi');
            redirect('/admin/karyawan');
        }

        $insertId = $this->model->create($data);
        if ($insertId) {
            if (!empty($_POST['create_account'])) {
                $username = $data['nik'] ?: $data['email'];
                $tempPassword = $this->generateTempPassword();
                if ($this->model->createAccount($insertId, $username, $tempPassword)) {
                    setFlash('success', 'Karyawan & akun dibuat. Username: ' . htmlspecialchars($username) . ' | Temp Password: ' . htmlspecialchars($tempPassword));
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
            setFlash('error', 'ID tidak valid');
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
            setFlash('error', 'NIK, Nama, dan Email wajib diisi');
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
     */
    public function delete() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
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
     */
    public function activateAccount() {
        $this->ensureAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/karyawan');
        }
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
        $username = $karyawan['nik'] ?: $karyawan['email'];
        $tempPassword = $this->generateTempPassword();
        if ($this->model->createAccount($karyawanId, $username, $tempPassword)) {
            setFlash('success', 'Akun diaktifkan. Username: ' . htmlspecialchars($username) . ' | Temp Password: ' . htmlspecialchars($tempPassword));
        } else {
            setFlash('error', 'Gagal mengaktifkan akun');
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
     * @param int $length
     * @return string
     */
    private function generateTempPassword() {
        $pw = bin2hex(random_bytes(5)); // 10 karakter random
        return $pw;
    }
}

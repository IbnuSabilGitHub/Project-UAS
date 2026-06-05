<?php

// Pastikan hanya bisa dijalankan lewat CLI (terminal)
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.0 403 Forbidden');
    die('Akses ditolak.');
}

require_once dirname(__DIR__) . '/app/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Daftar 5 akun admin dummy
$dummyAdmins = [
    [
        'email' => 'kelompok3@gmail.com',
        'password' => 'admin123',
        'role' => 'super_admin'
    ],
    [
        'email' => 'admin4@gmail.com',
        'password' => 'admin123',
        'role' => 'super_admin'
    ],
    [
        'email' => 'admin1@gmail.com',
        'password' => 'admin123',
        'role' => 'super_admin'
    ],
    [
        'email' => 'admin2@gmail.com',
        'password' => 'admin123',
        'role' => 'super_admin'
    ],
    [
        'email' => 'admin3@gmail.com',
        'password' => 'admin123',
        'role' => 'super_admin'
    ]
];

echo "=== Memulai Seeding 5 Akun Admin Dummy ===\n\n";

foreach ($dummyAdmins as $admin) {
    $email = $admin['email'];
    $password = $admin['password'];
    $role = $admin['role'];

    // Cek apakah email sudah terdaftar
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "[INFO] Email '{$email}' sudah terdaftar. Melewati...\n";
        $checkStmt->close();
        continue;
    }
    $checkStmt->close();

    // Enkripsi password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $karyawan_id = null;

    // Insert ke tabel users
    $stmt = $conn->prepare("
        INSERT INTO users (email, password_hash, role, karyawan_id)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sssi", $email, $password_hash, $role, $karyawan_id);

    if ($stmt->execute()) {
        echo "[SUKSES] Akun Admin berhasil dibuat: {$email} (Password: {$password})\n";
    } else {
        echo "[GAGAL] Gagal membuat akun '{$email}': " . $stmt->error . "\n";
    }
    $stmt->close();
}

$conn->close();
echo "\n=== Seeding Selesai ===\n";

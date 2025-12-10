<?php

// Cek apakah akses diizinkan
$allowed = false;

// Jika diakses via CLI (command line) ini diizinkan
if (php_sapi_name() === 'cli') {
    $allowed = true;
}

// Jika diakse via web, tolak
if (!$allowed) {
    header('HTTP/1.0 403 Forbidden');
    die('Akses ditolak.');
}


// Peringatan: File ini hanya untuk keperluan sementara dalam pengaturan database awal. karena feature pendaftaran belum diimplementasikan.
require_once dirname(__DIR__) . '/app/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();
$email = 'ibnu@gmail.com';
$password = 'ibnu123';
$role = 'super_admin'; // atau 'karyawan'

if($role === 'admin' || $role === 'super_admin') {
    $karyawan_id = null; // admin tidak terkait dengan karyawan manapun
} else {
    echo "Untuk membuat user karyawan, buat dulu data karyawannya";
}

# enkripsi password
$password_hash = password_hash($password, PASSWORD_BCRYPT);

# statemen insert
$stmt = $conn->prepare("
    INSERT INTO users (email, password_hash, role, karyawan_id)
    VALUES (?, ?, ?, ?)"
);

// bind parameter dan eksekusi
$stmt->bind_param("sssi", $email, $password_hash, $role, $karyawan_id);

if ($stmt->execute()) {
    echo "User berhasil ditambahkan. ID: " . $stmt->insert_id;
} else {
    echo "Gagal menambah user: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!-- 
run file ini via command line:
cd .\scripts\
php -d extension=mysqli register.php
-->
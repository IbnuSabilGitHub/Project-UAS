<?php

// Cek apakah akses diizinkan
$allowed = false;

// Jika diakses via CLI (command line) ini diizinkan
if (php_sapi_name() === 'cli') {
    $allowed = true;
}

// Jika diakses via web, tolak
if (!$allowed) {
    header('HTTP/1.0 403 Forbidden');
    die('Akses ditolak.');
}

// Script untuk inject mock data karyawan ke database
require_once dirname(__DIR__) . '/app/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Baca file MOCK_DATA.json
$jsonFile = __DIR__ . '/MOCK_DATA.json';
if (!file_exists($jsonFile)) {
    die("File MOCK_DATA.json tidak ditemukan!\n");
}

$jsonContent = file_get_contents($jsonFile);
$mockData = json_decode($jsonContent, true);

if (!$mockData) {
    die("Gagal membaca data JSON!\n");
}

// Function untuk generate NIK random 16 digit
function generateNIK()
{
    $nik = '';
    for ($i = 0; $i < 16; $i++) {
        $nik .= rand(0, 9);
    }
    return $nik;
}

// Function untuk generate unique NIK
function generateUniqueNIK($conn, $maxAttempts = 100)
{
    for ($i = 0; $i < $maxAttempts; $i++) {
        $nik = generateNIK();

        // Cek apakah NIK sudah ada di database
        $checkStmt = $conn->prepare("SELECT id FROM karyawan WHERE nik = ?");
        $checkStmt->bind_param("s", $nik);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            $checkStmt->close();
            return $nik;
        }
        $checkStmt->close();
    }

    die("Gagal generate NIK unik setelah $maxAttempts percobaan!\n");
}

// Prepare statement untuk insert
$stmt = $conn->prepare("
    INSERT INTO karyawan (nik, name, email, phone, position, join_date, status, employment_status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Gagal prepare statement: " . $conn->error . "\n");
}

$totalData = count($mockData);
$successCount = 0;
$failedCount = 0;

echo "Memulai proses inject data karyawan...\n";
echo "Total data: $totalData\n\n";

foreach ($mockData as $index => $data) {
    // Generate NIK unik
    $nik = generateUniqueNIK($conn);

    // Data dari JSON
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $join_date = $data['join_date'];

    // Position (bisa di-random atau set default)
    $positions = [
        'Backend Developer',
        'Frontend Developer',
        'Fullstack Developer',
        'DevOps / Cloud Engineer',
        'QA / Software Tester'
    ];

    $position = $positions[array_rand($positions)];

    // Status: 80% active, 20% inactive
    $rand = rand(1, 100);
    $status = ($rand <= 80) ? 'active' : 'inactive';

    // Employment Status: 5% resigned, 95% active
    $rand = rand(1, 100);
    $employment_status = ($rand <= 5) ? 'resigned' : 'active';

    // Bind dan execute
    $stmt->bind_param(
        "ssssssss",
        $nik,
        $name,
        $email,
        $phone,
        $position,
        $join_date,
        $status,
        $employment_status
    );

    if ($stmt->execute()) {
        $successCount++;
        echo "✓ [" . ($index + 1) . "/$totalData] Berhasil: $name (NIK: $nik)\n";
    } else {
        $failedCount++;
        echo "✗ [" . ($index + 1) . "/$totalData] Gagal: $name - Error: " . $stmt->error . "\n";
    }
}

$stmt->close();
$conn->close();

echo "\n========================================\n";
echo "Proses selesai!\n";
echo "Berhasil: $successCount\n";
echo "Gagal: $failedCount\n";
echo "========================================\n";

?>

<!-- 
Cara menjalankan file ini via command line:
cd scripts
php -d extension=mysqli inject_karyawan.php
-->
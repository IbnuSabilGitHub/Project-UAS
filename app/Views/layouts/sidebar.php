<?php
// Start session to access user role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine user role and include appropriate sidebar
if (isset($_SESSION['role']) && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    
    // Include appropriate sidebar based on role
    if (in_array($role, ['admin', 'super_admin'])) {
        require_once __DIR__ . '/sidebar-admin.php';
    } elseif ($role === 'karyawan') {
        require_once __DIR__ . '/sidebar-karyawan.php';
    }
}
?>
<?php
// Start session to access user role
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine user role and include appropriate sidebar
if (isset($_SESSION['role']) && isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $role = $_SESSION['role'];
    
    // Include appropriate sidebar based on role
    if (in_array($role, ['admin', 'super_admin'])) {
        // Get pending leave requests count for admin sidebar
        $pendingCount = 0;
        try {
            require_once __DIR__ . '/../../Models/PengajuanCuti.php';
            $cutiModel = new PengajuanCuti();
            $pendingCount = $cutiModel->countPending();
        } catch (Exception $e) {
            // Fallback to 0 if there's an error
            $pendingCount = 0;
        }
        
        require_once __DIR__ . '/sidebar-admin.php';
    } elseif ($role === 'karyawan') {
        require_once __DIR__ . '/sidebar-karyawan.php';
    }
}
?>
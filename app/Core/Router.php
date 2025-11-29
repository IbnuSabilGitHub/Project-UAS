<?php

class Router {
    public function resolve($uri, $method) {

        // Route untuk halaman index (pilihan login)
        if ($uri === "/") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->index();
            return;
        }

        // Route untuk halaman login admin (GET)
        if ($uri === "/admin/login" && $method === "GET") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->adminLoginPage();
            return;
        }

        // Route untuk proses login admin (POST)
        if ($uri === "/admin/login" && $method === "POST") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->adminLogin();
            return;
        }

        // Route untuk halaman login karyawan (GET)
        if ($uri === "/karyawan/login" && $method === "GET") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->karyawanLoginPage();
            return;
        }

        // Route untuk proses login karyawan (POST)
        if ($uri === "/karyawan/login" && $method === "POST") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->karyawanLogin();
            return;
        }

        // Route untuk halaman login lama (backward compatibility)
        if ($uri === "/login" && $method === "GET") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->loginPage();
            return;
        }

        // Route untuk proses login lama (POST)
        if ($uri === "/login" && $method === "POST") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->login();
            return;
        }

        // Route untuk dashboard 
        if ($uri === "/dashboard") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->dashboard();
            return;
        }

        // Admin dashboard
        if ($uri === "/admin/dashboard") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->adminDashboard();
            return;
        }

        // Karyawan dashboard
        if ($uri === "/karyawan/dashboard") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->employeeDashboard();
            return;
        }

        // Route untuk logout
        if ($uri === "/logout") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->logout();
            return;
        }

        // Routes untuk manajemen karyawan (admin)
        if (strpos($uri, '/admin/karyawan') === 0) {
            require_once __DIR__ . "/../Controllers/KaryawanController.php";
            $kc = new KaryawanController();

            if ($uri === '/admin/karyawan' && $method === 'GET') {
                $kc->index();
                return;
            }

            if ($uri === '/admin/karyawan/create' && $method === 'GET') {
                $kc->create();
                return;
            }

            if ($uri === '/admin/karyawan/store' && $method === 'POST') {
                $kc->create_account();
                return;
            }

            if ($uri === '/admin/karyawan/edit' && $method === 'GET') {
                $kc->edit();
                return;
            }

            if ($uri === '/admin/karyawan/update' && $method === 'POST') {
                $kc->update();
                return;
            }

            if ($uri === '/admin/karyawan/delete' && $method === 'POST') {
                $kc->delete();
                return;
            }
                if ($uri === '/admin/karyawan/deactivate' && $method === 'POST') {
                    $kc->deactivate();
                    return;
                }
            if ($uri === '/admin/karyawan/activate' && $method === 'POST') {
                $kc->activateAccount();
                return;
            }
        }

        // Routes untuk pengajuan cuti (admin)
        if (strpos($uri, '/admin/cuti') === 0) {
            require_once __DIR__ . "/../Controllers/CutiController.php";
            $cc = new CutiController();

            if ($uri === '/admin/cuti' && $method === 'GET') {
                $cc->index();
                return;
            }

            if ($uri === '/admin/cuti/approve' && $method === 'POST') {
                $cc->approve();
                return;
            }

            if ($uri === '/admin/cuti/reject' && $method === 'POST') {
                $cc->reject();
                return;
            }

            if ($uri === '/admin/cuti/delete' && $method === 'POST') {
                $cc->delete();
                return;
            }
        }

        if ($uri === '/change-password') {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            $ac = new AuthController();
            if ($method === 'GET') {
                $ac->changePasswordPage();
                return;
            }
            if ($method === 'POST') {
                $ac->changePassword();
                return;
            }
        }

        // Routes untuk Attendance (karyawan)
        if (strpos($uri, '/karyawan/attendance') === 0) {
            require_once __DIR__ . "/../Controllers/AttendanceController.php";
            $ac = new AttendanceController();

            if ($uri === '/karyawan/attendance' && $method === 'GET') {
                $ac->index();
                return;
            }

            if ($uri === '/karyawan/attendance/checkin' && $method === 'POST') {
                $ac->checkIn();
                return;
            }

            if ($uri === '/karyawan/attendance/checkout' && $method === 'POST') {
                $ac->checkOut();
                return;
            }
        }

        // Routes untuk Attendance (admin)
        if (strpos($uri, '/admin/attendance') === 0) {
            require_once __DIR__ . "/../Controllers/AttendanceController.php";
            $ac = new AttendanceController();

            if ($uri === '/admin/attendance' && $method === 'GET') {
                $ac->adminIndex();
                return;
            }

            if ($uri === '/admin/attendance/export' && $method === 'GET') {
                $ac->export();
                return;
            }
        }

        // Routes untuk Leave Request (karyawan)
        if (strpos($uri, '/karyawan/leave') === 0) {
            require_once __DIR__ . "/../Controllers/LeaveController.php";
            $lc = new LeaveController();

            if ($uri === '/karyawan/leave' && $method === 'GET') {
                $lc->index();
                return;
            }

            if ($uri === '/karyawan/leave/create' && $method === 'GET') {
                $lc->create();
                return;
            }

            if ($uri === '/karyawan/leave/store' && $method === 'POST') {
                $lc->store();
                return;
            }

            if ($uri === '/karyawan/leave/delete' && $method === 'POST') {
                $lc->delete();
                return;
            }
        }

        // Routes untuk view/preview file dengan authenticasi
        if (strpos($uri, '/file/leave/') === 0) {
            require_once __DIR__ . "/../Controllers/FileController.php";
            $fc = new FileController();
            
            // Extract ID dari URL: /file/leave/{id}
            $parts = explode('/', trim($uri, '/'));
            if (isset($parts[2]) && is_numeric($parts[2])) {
                $leaveId = (int)$parts[2];
                $fc->viewLeaveAttachment($leaveId);
                return;
            }
            
            http_response_code(400);
            echo "Bad Request: Invalid file URL";
            return;
        }

        // 404 Not Found
        http_response_code(404);
        require_once __DIR__ . "/../Views/errors/404.php";
        exit;
        
    }
}



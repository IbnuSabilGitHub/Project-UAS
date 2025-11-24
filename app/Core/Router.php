<?php

class Router {
    public function resolve($uri, $method) {

        // Route untuk halaman login (GET)
        if ($uri === "/" || ($uri === "/login" && $method === "GET")) {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->loginPage();
            return;
        }

        // Route untuk proses login (POST)
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

            if ($uri === '/admin/cuti/create' && $method === 'GET') {
                $cc->create();
                return;
            }

            if ($uri === '/admin/cuti/store' && $method === 'POST') {
                $cc->store();
                return;
            }

            if ($uri === '/admin/cuti/edit' && $method === 'GET') {
                $cc->edit();
                return;
            }

            if ($uri === '/admin/cuti/update' && $method === 'POST') {
                $cc->update();
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

        // 404 Not Found
        http_response_code(404);
        echo "<h1>404 - Halaman tidak ditemukan</h1>";
    }
}


<?php

class Router {
    public function resolve($uri, $method) {
        // Route untuk proses login (POST)
        if ($uri === "/login" && $method === "POST") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->login();
            return;
        }

        // Route untuk halaman login (GET)
        if ($uri === "/" || ($uri === "/login" && $method === "GET")) {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->loginPage();
            return;
        }

        // Route untuk dashboard
        if ($uri === "/dashboard") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->dashboard();
            return;
        }

        // Route untuk logout
        if ($uri === "/logout") {
            require_once __DIR__ . "/../Controllers/AuthController.php";
            (new AuthController())->logout();
            return;
        }

        // 404 Not Found
        http_response_code(404);
        echo "<h1>404 - Halaman tidak ditemukan</h1>";
    }
}


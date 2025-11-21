<?php
// Normalisasi base path agar include bekerja walau file ini di-require dari root index.
$baseDir = dirname(__DIR__); // C:\Users\MYPC\coding\HRIS
require_once $baseDir . "/app/config.php";
require_once $baseDir . "/app/Core/Router.php";

// Pastikan session dimulai sebelum ada output HTML apapun.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Parse URI untuk menghilangkan query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router = new Router();
$router->resolve($uri, $_SERVER['REQUEST_METHOD']);
<?php
// Normalisasi base path agar include bekerja walau file ini di-require dari root index.
$baseDir = dirname(__DIR__); // C:\Users\MYPC\coding\HRIS
require_once $baseDir . "/app/config.php";
require_once $baseDir . "/app/Core/Router.php";
require_once $baseDir . "/app/Core/Helpers.php";

// Pastikan session dimulai sebelum ada output HTML apapun.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Parse URI untuk menghilangkan query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Jika aplikasi dijalankan dari subfolder (mis. /HRIS),
// hapus base path dari URI sehingga Router menerima path yang diharapkan.
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); // e.g. /HRIS/index.php or /HRIS/public/index.php
$basePath = dirname($scriptName); // e.g. /HRIS or /HRIS/public or /
$basePath = str_replace('/public', '', $basePath); // jika front controller berada di /public
$basePath = rtrim($basePath, '/');
if ($basePath === '') {
	$basePath = '/';
}

// Simpan BASE_PATH sebagai constant agar bisa diakses dari Controller
if (!defined('BASE_PATH')) {
	define('BASE_PATH', $basePath);
}

if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
	$uri = substr($uri, strlen($basePath));
	if ($uri === '') {
		$uri = '/';
	}
}

$router = new Router();
$router->resolve($uri, $_SERVER['REQUEST_METHOD']);
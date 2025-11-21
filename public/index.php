<?php
$baseDir = dirname(__DIR__); 
require_once $baseDir . "/app/config.php";
require_once $baseDir . "/app/Core/Router.php";
require_once $baseDir . "/app/Core/Helpers.php";

// Pastikan session dimulai sebelum ada output HTML apapun.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Parse URI untuk menghilangkan query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']); 
$basePath = dirname($scriptName);
$basePath = str_replace('/public', '', $basePath);
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
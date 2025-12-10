<?php

/**
 * Deteksi apakah document root sudah di folder public/ atau masih di root project
 * 
 * @return bool True jika document root di folder public/
 */
function is_public_document_root() {
    // Cek apakah SCRIPT_FILENAME mengandung '/public/' atau '\public\'
    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    
    // Jika script berada di folder public, maka document root sudah di public/
    return strpos($scriptPath, '/public/') !== false;
}

/**
 * Ambil base URL secara otomatis untuk berbagai environment:
 * - XAMPP/htdocs: http://localhost/HRIS
 * - PHP Built-in Server: http://localhost:8000
 * - Virtual Host: http://hris.local
 */
function base_url() {
    $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 
              ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Path ke folder project (misal: /HRIS atau /)
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $scriptDir = rtrim(dirname($scriptName), '/');

    // Jika document root sudah di public/, hapus '/public' dari path
    if (is_public_document_root() && substr($scriptDir, -7) === '/public') {
        $scriptDir = substr($scriptDir, 0, -7);
    }

    // Jika root "/", kosongkan saja
    if ($scriptDir === '/' || $scriptDir === '.') {
        $scriptDir = '';
    }

    return $scheme . '://' . $host . $scriptDir;
}

/**
 * Redirect universal - bekerja di semua environment
 * 
 * @param string $path Path tujuan redirect (dengan atau tanpa leading slash)
 */
function redirect($path) {
    $baseUrl = base_url();
    $cleanPath = '/' . ltrim($path, '/');
    
    // Parse base_url untuk cek path component
    $parsedUrl = parse_url($baseUrl);
    $basePath = $parsedUrl['path'] ?? '';
    
    // Jika base path kosong atau hanya "/", buat absolute URL manual
    if (empty($basePath) || $basePath === '/') {
        $scheme = $parsedUrl['scheme'] ?? 'http';
        $host = $parsedUrl['host'] ?? 'localhost';
        $port = isset($parsedUrl['port']) && $parsedUrl['port'] != 80 && $parsedUrl['port'] != 443 
                ? ':' . $parsedUrl['port'] 
                : '';
        $url = $scheme . '://' . $host . $port . $cleanPath;
    } else {
        // Jika ada base path (misal: /HRIS), gabungkan
        $url = rtrim($baseUrl, '/') . $cleanPath;
    }
    
    header("Location: $url");
    exit;
}

/**
 * Generate URL universal - bekerja di semua environment
 * 
 * Menghindari double slash pada root domain
 * 
 * @param string $path Path yang akan dijadikan URL (dengan atau tanpa leading slash)
 * @return string Full URL atau relative path
 */
function url($path) {
    $baseUrl = base_url();
    $cleanPath = '/' . ltrim($path, '/');
    
    // Parse base_url untuk cek apakah ada path component
    $parsedUrl = parse_url($baseUrl);
    $basePath = $parsedUrl['path'] ?? '';
    
    // Jika base path kosong atau hanya "/", return hanya clean path
    if (empty($basePath) || $basePath === '/') {
        return $cleanPath;
    }
    
    // Jika ada base path (misal: /HRIS), gabungkan
    return rtrim($baseUrl, '/') . $cleanPath;
}

/**
 * Asset URL generator - otomatis deteksi environment
 * 
 * Menghasilkan path yang benar untuk:
 * - XAMPP/htdocs: /HRIS/public/assets/css/output.css
 * - PHP Built-in Server (public): /assets/css/output.css
 * - Virtual Host (public): /assets/css/output.css
 * 
 * @param string $path Path relatif dari folder assets/ (misal: 'css/output.css')
 * @return string Full URL atau relative path ke asset
 */
function asset($path) {
    $baseUrl = base_url();
    $cleanPath = ltrim($path, '/');
    
    // Tentukan asset path berdasarkan document root
    if (is_public_document_root()) {
        $assetPath = '/assets/' . $cleanPath;
    } else {
        $assetPath = '/public/assets/' . $cleanPath;
    }
    
    // Parse base_url untuk cek apakah ada path component
    $parsedUrl = parse_url($baseUrl);
    $basePath = $parsedUrl['path'] ?? '';
    
    // Jika base path kosong atau hanya "/", return hanya asset path
    if (empty($basePath) || $basePath === '/') {
        return $assetPath;
    }
    
    // Jika ada base path (misal: /HRIS), gabungkan
    return rtrim($baseUrl, '/') . $assetPath;
}

/**
 * Set flash message untuk ditampilkan di page berikutnya
 * 
 * @param string $type Tipe pesan: 'success', 'error', 'warning', 'info'
 * @param string $message Pesan yang akan ditampilkan
 */
function setFlash($type, $message) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Ambil flash message dan hapus dari session
 * 
 * @return array|null Array dengan 'type' dan 'message', atau null jika tidak ada
 */
function getFlash() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    
    return null;
}

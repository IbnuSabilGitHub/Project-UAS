<?php

/**
 * Ambil base URL secara otomatis, baik di htdocs/HRIS maupun localhost:8000
 */
function base_url() {
    $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Path ke folder project (misal: /HRIS atau /)
    $scriptDir = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');

    // Jika root "/", kosongkan saja
    if ($scriptDir === '/') {
        $scriptDir = '';
    }

    return $scheme . '://' . $host . $scriptDir;
}

/**
 * Redirect universal
 */
function redirect($path) {
    $url = rtrim(base_url(), '/') . '/' . ltrim($path, '/');
    header("Location: $url");
    exit;
}

/**
 * Generate URL universal
 */
function url($path) {
    return rtrim(base_url(), '/') . '/' . ltrim($path, '/');
}

/**
 * Asset universal
 */
function asset($path) {
    return rtrim(base_url(), '/') . '/public/assets/' . ltrim($path, '/');
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

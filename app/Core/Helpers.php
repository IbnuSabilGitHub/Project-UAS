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

<?php

/**
 * Helper function untuk redirect dengan base path otomatis
 * @param string $path Path relatif (misal: '/login', '/dashboard')
 */
function redirect($path) {
    // Pastikan path dimulai dengan /
    $path = '/' . ltrim($path, '/');
    header('Location: ' . $path);
    exit;
}


/**
 * Helper function untuk generate URL dengan base path otomatis
 * @param string $path Path relatif (misal: '/login', '/dashboard')
 * @return string URL lengkap dengan base path
 */
function url($path) {
    // Pastikan path dimulai dengan / untuk absolute path
    return '/' . ltrim($path, '/');
}

/**
 * Helper function untuk generate asset URL
 * @param string $path Path relatif ke asset (misal: 'css/output.css')
 * @return string URL lengkap ke asset
 */
function asset($path) {
    // Normalize path: hapus leading slash
    $path = ltrim($path, '/');
    
    // Return absolute path dari root
    return '/assets/' . $path;
}

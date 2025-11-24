<?php

/**
 * Helper function untuk redirect dengan base path otomatis
 * @param string $path Path relatif
 */
function redirect($path) {
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}


/**
 * Helper function untuk generate URL dengan base path otomatis
 * @param string $path Path relatif
 * @return string URL lengkap dengan base path
 */
function url($path) {
    $basePath = defined('BASE_PATH') ? BASE_PATH : '';
    if ($basePath === '/') {
        $basePath = '';
    }
    return $basePath . $path;
}

/**
 * Helper function untuk generate asset URL
 * @param string $path Path relatif ke asset (misal: 'css/output.css')
 * @return string URL lengkap ke asset
 */
function asset($path) {
    $basePath = defined('BASE_PATH') ? BASE_PATH : '';
    if ($basePath === '/') {
        $basePath = '';
    }
    return $basePath . '/public/assets/' . ltrim($path, '/');
}

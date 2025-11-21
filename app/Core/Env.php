<?php

class Env {
    public static function load($path) {
        # Jika file .env tidak ada, hentikan proses
        if (!file_exists($path)) return;

        # Bacva file .env baris per baris(abaikan baris kosong dan hapus newline)
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            if(str_starts_with(trim($line), '#')) continue; # Abaikan komentar

            # split key dan value berdasarkan tanda '=' dan simpan ke dalam variabel global $_ENV
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}
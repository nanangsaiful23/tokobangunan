<?php

/**
 * Router script untuk PHP built-in development server.
 *
 * Laravel versi baru tidak lagi menyertakan server.php bawaan, padahal
 * tanpa router script, PHP built-in server akan langsung menolak (404)
 * setiap request yang "kelihatan seperti" file statis (berakhiran .js,
 * .css, dll) yang tidak ada secara fisik di folder public/ — padahal
 * banyak library seperti Livewire menyajikan file semacam itu secara
 * dinamis lewat route Laravel, bukan file fisik.
 *
 * Cara pakai (dari root project, BUKAN dari dalam folder public/):
 *   php8 -S 127.0.0.1:9000 router.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Kalau memang ada file fisik yang cocok di public/ (misal gambar,
// favicon, dll), biarkan PHP built-in server menyajikannya langsung.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Selain itu (termasuk route dinamis seperti /pos, /livewire-xxx/livewire.js,
// dll), selalu teruskan ke Laravel lewat index.php.
require_once __DIR__.'/public/index.php';

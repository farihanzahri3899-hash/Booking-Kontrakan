<?php

// Pastikan direktori penyimpanan di /tmp tersedia untuk lingkungan serverless Vercel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/storage/app/public',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Teruskan permintaan Vercel ke entry point publik Laravel
require __DIR__ . '/../public/index.php';

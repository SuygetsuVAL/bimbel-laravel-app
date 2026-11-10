<?php

// Salin database SQLite yang sudah di-seed ke /tmp (direktori yang writable di Vercel)
$tmpDb = '/tmp/database.sqlite';
$srcDb = __DIR__ . '/../database/database.sqlite';

if (!file_exists($tmpDb) || filesize($tmpDb) === 0) {
    if (file_exists($srcDb) && filesize($srcDb) > 0) {
        copy($srcDb, $tmpDb);
    } else {
        touch($tmpDb);
    }
}

// Pastikan folder compiled views Blade tersedia di /tmp
if (!is_dir('/tmp/views')) {
    @mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';

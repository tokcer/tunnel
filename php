<?php
// 1. Ambil slug dari URL
$slug = isset($_GET['slug']) ? trim($_GET['slug'], '/') : '';

// 2. Jika diakses tanpa slug (Halaman Utama / Home), panggil file home.php
if (empty($slug)) {
    if (file_exists('home.php')) {
        include('home.php');
    } else {
        echo "<h1>File home.php tidak ditemukan!</h1>";
    }
    exit; // Wajib ada agar script programmatic SEO di bawahnya tidak ikut berjalan
}

// ==========================================
// 3. LOGIKA VIRTUAL SLUG & SEO BERJALAN DI BAWAH INI:
// ==========================================
$brand1_lines = file_exists("brand1.txt") ? file("brand1.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$brand2_lines = file_exists("brand2.txt") ? file("brand2.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

// Tentukan brand1 berdasarkan crc32 slug
if (!empty($brand1_lines)) {
    $index1 = abs(crc32($slug . "_b1")) % count($brand1_lines);
    $current_brand1 = trim($brand1_lines[$index1]);
} else {
    $current_brand1 = "Brand 1";
}

// Tentukan brand2 berdasarkan crc32 slug
if (!empty($brand2_lines)) {
    $index2 = abs(crc32($slug . "_b2")) % count($brand2_lines);
    $current_brand2 = trim($brand2_lines[$index2]);
} else {
    $current_brand2 = "Brand 2";
}

// Ambil kalimat pelengkap
$pelengkap_file = "pelengkap.txt";
if (file_exists($pelengkap_file)) {
    $pelengkap_lines = file($pelengkap_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!empty($pelengkap_lines)) {
        $index_pelengkap = abs(crc32($slug)) % count($pelengkap_lines);
        $pelengkap = $pelengkap_lines[$index_pelengkap];
    } else {
        $pelengkap = "Solusi Terbaik Untuk Kebutuhan Anda";
    }
} else {
    $pelengkap = "Solusi Terbaik Untuk Kebutuhan Anda";
}

$finalTitle = $current_brand1 . " X " . $current_brand2 . " - " . $pelengkap;
$finalDesc = "Dapatkan informasi lengkap mengenai " . $current_brand1 . " dan " . $current_brand2 . ". " . $pelengkap;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$publisherName = $current_brand1 . ' ' . $current_brand2; 
$finalKeywords = $current_brand1 . ", " . $current_brand2;

$ampUrl = "https://coba.usageswab.xyz/" . $slug;
?>

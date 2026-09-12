<?php
header("Content-Type: application/xml; charset=utf-8");

// 1. Deteksi Domain & Protokol otomatis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';

// 2. Format tanggal untuk lastmod (Waktu saat ini dalam format ISO 8601)
$currentDate = date('Y-m-d\TH:i:s+00:00');

// 3. Baca file sumber data
$brand1_lines = file_exists("brand1.txt") ? file("brand1.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$brand2_lines = file_exists("brand2.txt") ? file("brand2.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

$brand1_lines = array_map('trim', $brand1_lines);
$brand2_lines = array_map('trim', $brand2_lines);

// 4. Cetak struktur XML sesuai standar
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
echo '<!-- created with Custom PHP Dynamic Sitemap Generator -->' . PHP_EOL;

// 5. Masukkan Halaman Utama (Homepage) dengan priority 1.00
echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($baseUrl) . "</loc>\n";
echo "    <lastmod>" . $currentDate . "</lastmod>\n";
echo "    <priority>1.00</priority>\n";
echo "  </url>\n";

// 6. Generate URL Exact Match dengan priority 1.00 untuk semua kombinasi
foreach ($brand1_lines as $b1) {
    foreach ($brand2_lines as $b2) {
        $slug = strtolower(str_replace(' ', '-', $b1)) . '-' . strtolower(str_replace(' ', '-', $b2));
        $loc = $baseUrl . $slug;

        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "    <lastmod>" . $currentDate . "</lastmod>\n";
        echo "    <priority>1.00</priority>\n";
        echo "  </url>\n";
    }
}

echo '</urlset>';
?>

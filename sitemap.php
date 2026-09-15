<?php
header("Content-Type: application/xml; charset=utf-8");

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';
$currentDate = date('Y-m-d\TH:i:s+00:00');

$file1 = "brand1.txt";
$file2 = "brand2.txt";

$brand1 = file_exists($file1) ? file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$brand2 = file_exists($file2) ? file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

$brand1 = array_map('trim', $brand1);
$brand2 = array_map('trim', $brand2);

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// Homepage
echo "  <url>\n";
echo "    <loc>" . htmlspecialchars($baseUrl) . "</loc>\n";
echo "    <lastmod>" . $currentDate . "</lastmod>\n";
echo "    <priority>1.00</priority>\n";
echo "  </url>\n";

// Perulangan lurus sejajar (Bukan kali silang)
$max_count = max(count($brand1), count($brand2));

for ($i = 0; $i < $max_count; $i++) {
    $bA = isset($brand1[$i]) ? strtolower(str_replace(' ', '-', $brand1[$i])) : '';
    $bB = isset($brand2[$i]) && !empty(trim($brand2[$i])) ? strtolower(str_replace(' ', '-', $brand2[$i])) : '';

    if (empty($bA) && empty($bB)) continue;

    $slug = $bA . ($bB ? '-' . $bB : '');
    if (empty($bA)) { $slug = $bB; }

    $loc = $baseUrl . $slug;

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "    <lastmod>" . $currentDate . "</lastmod>\n";
    echo "    <priority>0.80</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
?>

<?php
/**
 * ============================================
 * pixhom - Debug فایل
 * ============================================
 */

echo "<h2>Debug pixhom</h2>";
echo "<pre style='background: #f5f5f5; padding: 20px; border-radius: 8px; direction: ltr;'>";

// بررسی فایل config
echo "1. بررسی config.php:<br>";
if (file_exists('config/config.php')) {
    echo "✓ فایل موجود است<br>";
} else {
    echo "✗ فایل وجود ندارد<br>";
}

// بررسی اتصال دیتابیس
echo "<br>2. بررسی اتصال دیتابیس:<br>";
try {
    require_once 'config/config.php';
    echo "✓ اتصال موفق<br>";
    
    // بررسی جداول
    echo "<br>3. بررسی جداول:<br>";
    $tables = ['portfolio', 'blog_posts', 'notifications', 'pages', 'site_settings'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'")->fetch();
        if ($result) {
            echo "✓ جدول $table موجود است<br>";
        } else {
            echo "✗ جدول $table موجود نیست<br>";
        }
    }
    
} catch(Exception $e) {
    echo "✗ خطا: " . $e->getMessage() . "<br>";
}

echo "</pre>";
echo "<br><a href='setup.php' style='background: #667eea; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;'>برو به Setup</a>";
?>

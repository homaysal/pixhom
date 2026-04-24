<?php
/**
 * فایل تصحیح جداول دیتابیس
 * این فایل جداول دیتابیس را بازنشانی کرده و دوباره ایجاد می‌کند
 * 
 * آدرس: https://ydek.ir/fix_database.php
 */

require_once 'config/config.php';

try {
    echo "<h2 style='color: #4a90e2;'>شروع تصحیح جداول دیتابیس...</h2>";
    
    // حذف جداول قدیمی
    $tables = ['custom_pages', 'notifications', 'blog_posts', 'portfolio'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table");
        echo "<p style='color: green;'>جدول $table حذف شد</p>";
    }
    
    // ایجاد جداول جدید
    $sql = file_get_contents('database/fix_tables.sql');
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo "<p style='color: green;'>✓ دستور اجرا شد</p>";
        }
    }
    
    echo "<h2 style='color: green;'>تمام جداول با موفقیت ایجاد شدند!</h2>";
    echo "<p><strong>حالا می‌توانید به <a href='admin/index.php'>پنل ادمین</a> بروید</strong></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>خطا: " . $e->getMessage() . "</h2>";
}
?>

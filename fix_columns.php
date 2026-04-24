<?php
// فیلد config
require_once 'config/config.php';

try {
    echo "<h2>اضافه کردن ستون‌های گم شده به جدول portfolio</h2>";
    
    // بررسی اینکه آیا ستون‌ها موجودند یا نه
    $checkSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='portfolio' AND COLUMN_NAME IN ('contact_phone', 'order_link')";
    $stmt = $pdo->query($checkSql);
    $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>ستون‌های موجود: " . implode(', ', $existingColumns) . "</p>";
    
    // اضافه کردن contact_phone اگر موجود نباشد
    if (!in_array('contact_phone', $existingColumns)) {
        echo "<p>اضافه کردن ستون contact_phone...</p>";
        $pdo->exec("ALTER TABLE portfolio ADD COLUMN contact_phone VARCHAR(20) NULL DEFAULT NULL");
        echo "<p style='color: green;'>✓ ستون contact_phone اضافه شد</p>";
    } else {
        echo "<p style='color: blue;'>ستون contact_phone قبلاً موجود است</p>";
    }
    
    // اضافه کردن order_link اگر موجود نباشد
    if (!in_array('order_link', $existingColumns)) {
        echo "<p>اضافه کردن ستون order_link...</p>";
        $pdo->exec("ALTER TABLE portfolio ADD COLUMN order_link VARCHAR(500) NULL DEFAULT NULL");
        echo "<p style='color: green;'>✓ ستون order_link اضافه شد</p>";
    } else {
        echo "<p style='color: blue;'>ستون order_link قبلاً موجود است</p>";
    }
    
    echo "<p style='color: green; font-size: 16px;'><strong>✓ تمام ستون‌ها اضافه شدند!</strong></p>";
    echo "<p><a href='portfolio.php'>بازگشت به صفحه نمونه کارها</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>خطا: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

# راهنمای نصب و استفاده pixhom

خوش‌آمدید به **pixhom**! این راهنما شما را مرحله‌به‌مرحله از نصب تا راه‌اندازی راهنمایی می‌کند.

---

## 📋 نیازمندی‌های سیستم

قبل از شروع، اطمینان حاصل کنید:
- **cPanel** فعال است
- **PHP 7.4+** فعال است
- **MySQL 5.7+** دسترسی دارید
- **FTP/SFTP** یا **File Manager** cPanel دارید

---

## 🚀 مراحل نصب

### **مرحله 1: آماده‌سازی پایگاه داده**

1. **وارد cPanel شوید** → سرچ کنید "MySQL Databases"
2. **ایجاد پایگاه داده جدید:**
   - نام: `yadakmor_pixhom_db`
   - کلیک "Create Database"

3. **ایجاد کاربر MySQL:**
   - Username: `yadakmor_pixhom`
   - Password: `aA38470236#@!`
   - کلیک "Create User"

4. **اختیارات دهید:**
   - Select User: `yadakmor_pixhom`
   - Select Database: `yadakmor_pixhom_db`
   - Select Privileges: **All**
   - کلیک "Make Changes"

5. **تنظیمات اتصال تایید شد:**
   - **Host:** `localhost`
   - **Database:** `yadakmor_pixhom_db`
   - **Username:** `yadakmor_pixhom`
   - **Password:** `aA38470236#@!`

---

### **مرحله 2: آپلود فایل‌ها**

1. **File Manager یا FTP استفاده کنید**
   - وارد `public_html` شوید
   - تمام فایل‌ها را آپلود کنید:
     ```
     public_html/
     ├── index.html
     ├── portfolio-detail.php
     ├── blog-post.php
     ├── css/
     │   └── style.css
     ├── js/
     │   └── main.js
     ├── admin/
     │   ├── index.php (ورود)
     │   ├── dashboard.php
     │   ├── portfolio.php
     │   ├── blog.php
     │   ├── settings.php
     │   ├── notifications.php
     │   ├── page-builder.php
     │   └── logout.php
     ├── config/
     │   └── config.php
     ├── api/
     │   ├── save-portfolio.php
     │   ├── save-blog.php
     │   ├── delete-item.php
     │   ├── get-data.php
     │   ├── add-notification.php
     │   ├── clear-notifications.php
     │   └── save-page.php
     ├── database/
     │   └── create_tables.sql
     └── uploads/
         ├── portfolio/
         └── blog/
     ```

---

### **مرحله 3: ایجاد جداول دیتابیس**

1. **وارد cPanel → MySQL → phpMyAdmin شوید**
2. **دیتابیس `yadakmor_pixhom_db` را انتخاب کنید**
3. **تب SQL کنید و این کدها را اجرا کنید:**

```sql
-- جدول نمونه کارها
CREATE TABLE IF NOT EXISTS portfolio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(100),
    image_url VARCHAR(255),
    image_path VARCHAR(255),
    demo_url VARCHAR(255),
    source_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_created (created_at)
);

-- جدول بلاگ
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created (created_at)
);

-- جدول اعلانات
CREATE TABLE IF NOT EXISTS notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50),
    message TEXT NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول صفحات سفارشی (Page Builder)
CREATE TABLE IF NOT EXISTS custom_pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
);
```

4. **کلیک "Execute"** و منتظر بمانید

---

### **مرحله 4: تنظیم فایل `config/config.php`**

فایل `config/config.php` از قبل تنظیم شده است:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'yadakmor_pixhom');
define('DB_PASS', 'aA38470236#@!');
define('DB_NAME', 'yadakmor_pixhom_db');
define('SITE_URL', 'https://ydek.ir');
```

---

## 🔐 ورود به پنل ادمینی

### **مسیر ورود:**
```
https://ydek.ir/admin/
```

### **اطلاعات پیش‌فرض:**
- **Username:** `admin`
- **Password:** `admin123`

⚠️ **مهم:** **فوراً رمز را تغییر دهید!**

---

## 📚 راهنمای استفاده پنل ادمینی

### **داشبورد (Dashboard)**
- نمایش آمار سایت (تعداد نمونه کارها، مقالات، اعلانات)
- لینک سریع به بخش‌های مختلف
- نمایش آخرین اضافه‌شده‌ها

### **مدیریت نمونه کارها (Portfolio)**
- ➕ **افزودن:** نمونه کار جدید
  - عنوان، دسته‌بندی (قالب/پلاگین/ابزار)
  - توضیح کامل
  - لینک دمو و لینک سورس
  - آپلود تصویر
- ✏️ **ویرایش:** تغییر اطلاعات
- 🗑️ **حذف:** حذف نمونه کار

### **مدیریت بلاگ (Blog)**
- ✍️ **ن��شتن مقاله:** محتوای جدید
  - عنوان
  - خلاصه (excerpt)
  - متن کامل مقاله
- 📝 **ویرایش:** تغییر مقالات
- 🗑️ **حذف:** حذف مقالات

### **Page Builder**
- 🎨 **ساخت صفحات سفارشی:**
  - درگ‌و‌درپ (drag & drop)
  - انتخاب از قالب‌های آماده
  - تنظیم layout و محتوا

### **اعلانات (Notifications)**
- 🔔 **مشاهده اعلانات:**
  - اعلانات سیستم
  - پیام‌های مهم
  - وضعیت عملیات‌ها

### **تنظیمات (Settings)**
- ⚙️ **اطلاعات سایت:**
  - نام سایت
  - توضیح کلی
  - ایمیل و شماره تماس

---

## 🎯 نکات مهم

### **پوشه‌های مهم:**
- `uploads/` - تصاویر آپلود شده (اجازه نوشتن لازم است)
- `admin/` - فایل‌های ادمینی (محافظت شده)
- `config/` - تنظیمات (محافظت شده)
- `database/` - فایل‌های SQL

### **تنظیمات اجازه‌ها (Permissions) در cPanel:**
```
uploads/               755
uploads/portfolio/     755
uploads/blog/          755
config/config.php      644
admin/                 755
api/                   755
```

### **امنیت:**
1. ✅ رمز ادمین را فوراً تغییر دهید
2. ✅ از HTTPS استفاده کنید
3. ✅ فایل `config.php` را دارای اطلاعات صحیح نگه دارید
4. ✅ تاریخ‌ها و مقالات را منظم بکنید

---

## 🆘 حل مشکلات

### **خطا: "خطا در اتصال: ❌"**
- **حل:** اطلاعات دیتابیس در `config.php` را بررسی کنید
  - Username: `yadakmor_pixhom`
  - Password: `aA38470236#@!`
  - Database: `yadakmor_pixhom_db`

### **خطا: "تمام فیلدها الزامی هستند"**
- **حل:** تمام فیلدهای فرم را پر کنید (عنوان، توضیح، محتوا)

### **آپلود تصویر کار نمی‌کند**
- حجم فایل را بررسی کنید (Max: 5MB)
- پسوند فایل تایید‌شده است؟ (JPG, PNG, GIF, WebP)
- پوشه‌ی `uploads/` وجود دارد یا نه؟
- Permissions پوشه‌ی uploads را به 755 تغییر دهید

### **صفحات نمایش داده نمی‌شوند**
- جداول دیتابیس ایجاد شده‌اند یا نه؟
- Errors را چک کنید: cPanel → Error Logs
- PHP version را بررسی کنید (باید 7.4+)

### **پنل ادمین وارد نمی‌شود**
- تاریخ و ساعت سرور را بررسی کنید
- فایل `admin/index.php` صحیح است یا نه؟
- به آدرس صحیح رفتید: `https://ydek.ir/admin/`

---

## 📞 فایل‌های مهم و توابع

### **فایل‌های API:**
- `api/save-portfolio.php` - ذخیره نمونه‌کار
- `api/save-blog.php` - ذخیره مقالات
- `api/delete-item.php` - حذف آیتم
- `api/add-notification.php` - اضافه کردن اعلان
- `api/save-page.php` - ذخیره صفحات سفارشی

### **فایل‌های Frontend:**
- `index.html` - صفحه اول
- `portfolio-detail.php` - صفحه جزئیات نمونه کار
- `blog-post.php` - صفحه مقاله بلاگ

---

**موفق باشید! 🎉**

> ایجاد شده برای pixhom - جایی که پیکسل‌ها خانه می‌سازند

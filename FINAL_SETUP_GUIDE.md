# راهنمای نصب و استفاده pixhom

## مرحله 1: آپلود فایل‌ها

1. تمام فایل‌های پروژه را به cPanel آپلود کنید
2. ساختار پوشه‌ها باید به این صورت باشد:
```
public_html/
├── admin/
│   ├── setup-database.php  (فایل جدید)
│   ├── index.php
│   ├── dashboard.php
│   ├── portfolio.php
│   ├── blog.php
│   ├── settings.php
│   └── logout.php
├── api/
│   ├── save-portfolio.php
│   ├── save-blog.php
│   ├── save-settings.php
│   └── delete-item.php
├── config/
│   └── config.php
├── css/
│   └── style.css
├── js/
│   └── main.js
├── uploads/  (پوشه خالی - سیستم فایل‌ها)
│   └── portfolio/
└── index.html
```

## مرحله 2: ایجاد جداول دیتابیس

**روش 1 (توصیه شده):**
1. در مرورگر خود به این آدرس بروید:
   ```
   https://ydek.ir/admin/setup-database.php
   ```
2. صفحه تمام جداول را ایجاد می‌کند
3. کافی است صفحه را یک‌بار بازدید کنید

**روش 2 (از طریق phpMyAdmin):**
1. به cPanel وارد شوید
2. phpMyAdmin را باز کنید
3. دیتابیس `yadakmor_pixhom_db` را انتخاب کنید
4. بر روی گزینه "SQL" کلیک کنید
5. این کد را کپی و جایگذاری کنید:

```sql
CREATE TABLE portfolio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
    description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci,
    category VARCHAR(100) COLLATE utf8mb4_unicode_ci,
    image_path VARCHAR(255),
    demo_url VARCHAR(255),
    source_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
    excerpt TEXT COLLATE utf8mb4_unicode_ci,
    content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci,
    category VARCHAR(100) COLLATE utf8mb4_unicode_ci,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media (
    id INT PRIMARY KEY AUTO_INCREMENT,
    file_path VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
    original_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
    file_type VARCHAR(100),
    file_size INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    `key` VARCHAR(100) UNIQUE NOT NULL COLLATE utf8mb4_unicode_ci,
    value LONGTEXT COLLATE utf8mb4_unicode_ci,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) COLLATE utf8mb4_unicode_ci,
    message TEXT NOT NULL COLLATE utf8mb4_unicode_ci,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE custom_pages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci,
    slug VARCHAR(255) UNIQUE NOT NULL COLLATE utf8mb4_unicode_ci,
    content LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## مرحله 3: ورود به پنل ادمینی

1. به این آدرس بروید:
   ```
   https://ydek.ir/admin/index.php
   ```

2. اطلاعات ورود:
   - **نام کاربری:** admin
   - **رمز عبور:** admin123

3. **به‌روزرسانی اطلاعات حساب:**
   - پس از ورود اول، به "تنظیمات" بروید
   - ایمیل و شماره تماس خود را تکمیل کنید

## مرحله 4: شروع کار

### افزودن نمونه کار (قالب/پلاگین):
1. بر روی "نمونه کارها" کلیک کنید
2. "نمونه کار جدید" را انتخاب کنید
3. فرم را تکمیل کنید:
   - **عنوان:** نام قالب یا پلاگین
   - **دسته‌بندی:** قالب / پلاگین / ابزار
   - **توضیح:** توضیح جزئیات
   - **تصویر:** از رسانه انتخاب کنید یا آپلود کنید
   - **لینک دمو:** آدرس دمو (اختیاری)
   - **لینک سورس:** آدرس دانلود (اختیاری)
4. "ذخیره" را کلیک کنید

### افزودن مقاله بلاگ:
1. بر روی "بلاگ" کلیک کنید
2. "مقاله جدید" را انتخاب کنید
3. فرم را تکمیل کنید:
   - **عنوان:** عنوان مقاله
   - **خلاصه:** خلاصه کوتاه
   - **متن:** متن کامل مقاله
4. "انتشار" را کلیک کنید

### مدیریت تصاویر/فایل‌ها:
1. بر روی "رسانه" کلیک کنید
2. تصاویر جدید را آپلود کنید
3. می‌توانید آن‌ها را در نمونه کارها و بلاگ استفاده کنید

## مرحله 5: تنظیمات سایت

1. به "تنظی��ات" بروید
2. اطلاعات سایت را تکمیل کنید:
   - **نام سایت:** pixhom
   - **توضیح:** معرفی سایت
   - **ایمیل تماس:** ایمیل شما
   - **شماره تماس:** تماس شما
3. "ذخیره تنظیمات" را کلیک کنید

---

## حل مشکلات

### مشکل: صفحه 500 Error نمایش می‌دهد
**حل:**
1. مطمئن شوید که جداول ایجاد شده‌اند
2. `admin/setup-database.php` را دوباره اجرا کنید
3. فایل `config/config.php` تنظیمات صحیح دارد

### مشکل: فارسی نمایش نمی‌دهد
**حل:**
- تمام فایل‌ها `UTF-8` هستند
- دیتابیس `utf8mb4_unicode_ci` استفاده می‌کند
- مشکل حل شده است!

### مشکل: سیو نمی‌شود
**حل:**
1. مطمئن شوید که جداول ایجاد شده‌اند
2. Browser Console را بررسی کنید (F12)
3. Network Tab را بررسی کنید

### مشکل: نمی‌تونم وارد پنل بشم
**حل:**
1. نام کاربری و رمز عبور را بررسی کنید
2. session ها فعال هستند
3. Cookies را پاک کنید و دوباره امتحان کنید

---

## نکات مهم

1. **پوشه uploads:** مطمئن شوید که `uploads` وجود دارد و مجوز نوشتن دارد
2. **UTF-8:** تمام فایل‌ها UTF-8 هستند - فارسی کار می‌کند!
3. **Session:** جلسات PHP فعال هستند
4. **API‌ها:** تمام API‌های Save-Blog، Save-Portfolio، Save-Settings کار می‌کنند
5. **تنظیمات:** همه جداول تنظیمات از دیتابیس می‌خوانند

---

## فایل‌های مهم

```
✓ admin/setup-database.php  - ایجاد جداول
✓ config/config.php         - اتصال دیتابیس  
✓ api/save-portfolio.php    - ذخیره نمونه کار
✓ api/save-blog.php         - ذخیره بلاگ
✓ api/save-settings.php     - ذخیره تنظیمات
✓ admin/portfolio.php       - صفحه مدیریت نمونه کار
✓ admin/blog.php            - صفحه مدیریت بلاگ
✓ admin/settings.php        - صفحه تنظیمات
```

---

## اطلاعات دیتابیس

- **Host:** localhost
- **نام دیتابیس:** yadakmor_pixhom_db
- **نام کاربری:** yadakmor_pixhom
- **پسورد:** aA38470236#@!
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

---

آماده‌اید! اگر سوالی داشتید، از پنل Support استفاده کنید.

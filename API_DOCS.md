# مستندات API pixhom

---

## 📡 نقاط پایانی (Endpoints)

### **نمونه کارها (Portfolio)**

#### لیست نمونه کارها
```
GET /api/portfolio.php?action=list
```
**پاسخ:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "قالب حرفه‌ای",
      "description": "توضیح",
      "category": "template",
      "image_url": "/uploads/portfolio/image.jpg",
      "demo_url": "https://demo.example.com",
      "download_url": "https://download.example.com",
      "created_at": "2026-01-01"
    }
  ]
}
```

#### اضافه‌کردن نمونه کار
```
POST /api/portfolio.php?action=add
```
**پارامترها:**
```
- title: عنوان (ضروری)
- description: توضیح
- category: دسته‌بندی (template/plugin/tool)
- demo_url: لینک دمو
- download_url: لینک دانلود
- image: فایل تصویر
```

---

### **بلاگ (Blog)**

#### لیست مقالات
```
GET /api/blog.php?action=list&limit=10&page=1
```

#### دریافت یک مقاله
```
GET /api/blog.php?action=get&id=1
```

#### اضافه‌کردن مقاله
```
POST /api/blog.php?action=add
```
**پارامترها:**
```
- title: عنوان (ضروری)
- content: محتوا (ضروری)
- category: دسته‌بندی
- featured_image: تصویر
- author: نویسنده
```

---

## 🔐 احراز هویت

تمام درخواست‌های **POST/PUT/DELETE** نیاز به **ورود** دارند:

```php
session_start();
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'عدم دسترسی']));
}
```

---

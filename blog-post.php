<?php
require_once 'config/config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header('Location: /#blog');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header('Location: /#blog');
        exit;
    }
} catch (Exception $e) {
    die('خطا: ' . $e->getMessage());
}

// تبدیل تاریخ
$date = new DateTime($post['created_at']);
$jalaliDate = $date->format('Y/m/d');

// فیچرهای بلاگ
$features = [];
if (!empty($post['features'])) {
    $features = array_filter(explode("\n", trim($post['features'])));
    $features = array_map('trim', $features);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - pixhom</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blog-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .post-header {
            padding: 60px 0;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%);
            animation: slideInUp 0.6s ease-out;
        }
        
        .post-title {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ec4899 0%, #06b6d4 50%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }
        
        .post-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
            flex-wrap: wrap;
        }
        
        .post-meta span {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(124, 58, 237, 0.1);
            border-radius: 8px;
            border-left: 2px solid var(--secondary);
        }

        .post-media-carousel {
            position: relative;
            background: var(--background);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            aspect-ratio: 16 / 9;
            width: 100%;
            margin: 2rem 0;
        }

        .media-carousel-container {
            display: flex;
            overflow: hidden;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .media-carousel-item {
            min-width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.05) 0%, rgba(6, 182, 212, 0.05) 100%);
            transition: transform 0.3s ease;
            width: 100%;
            height: 100%;
            flex-shrink: 0;
        }

        .media-carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .media-carousel-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 768px) {
            .post-media-carousel {
                aspect-ratio: 3 / 4;
            }
        }

        .media-carousel-controls {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 0.5rem;
            z-index: 10;
        }

        .media-carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .media-carousel-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .media-carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .media-carousel-button:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: translateY(-50%) scale(1.1);
        }

        .media-carousel-button.prev {
            left: 1rem;
        }

        .media-carousel-button.next {
            right: 1rem;
        }

        .media-counter {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            z-index: 10;
        }
        
        @media (max-width: 768px) {
            .post-header {
                padding: 40px 0;
            }
            
            .post-title {
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            
            .post-meta {
                gap: 1rem;
                flex-direction: column;
            }
            
            .post-meta span {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
            
            .post-content {
                margin: 40px auto;
                padding: 0 16px;
            }
            
            .post-content h2 {
                font-size: 1.5rem;
            }
            
            .media-carousel-button {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .media-carousel-button.prev {
                left: 0.5rem;
            }
            
            .media-carousel-button.next {
                right: 0.5rem;
            }
            
            .media-carousel-controls {
                bottom: 0.75rem;
                gap: 0.4rem;
            }
            
            .media-carousel-dot {
                width: 8px;
                height: 8px;
            }
            
            .media-counter {
                top: 0.75rem;
                right: 0.75rem;
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body class="blog-page">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="logo">
                    <h1>pixhom</h1>
                    <p>پیکسل‌ها خانه می‌سازند</p>
                </div>
                <a href="/#blog" style="color: var(--accent); text-decoration: none; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;">← بازگشت به بلاگ</a>
            </div>
        </div>
    </nav>

    <div class="post-header">
        <div class="container">
            <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="post-meta">
                <span>📅 <?php echo $jalaliDate; ?></span>
                <span>👤 توسط pixhom</span>
            </div>
        </div>
    </div>

    <div class="post-content">
        <h2>خلاصه</h2>
        <p><?php echo nl2br(htmlspecialchars($post['excerpt'])); ?></p>
        
        <?php if (!empty($features)): ?>
            <h2>نکات کلیدی</h2>
            <ul style="list-style-position: inside; line-height: 1.8;">
                <?php foreach ($features as $feature): ?>
                    <li style="margin-bottom: 0.5rem;">✓ <?php echo htmlspecialchars($feature); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <!-- نمایش عکس‌ها و ویدیوها -->
        <?php
        $media_files = [];
        if (!empty($post['media_files'])) {
            $media_files = json_decode($post['media_files'], true) ?: [];
        }
        
        if (!empty($media_files)): ?>
            <h2>تصاویر و فیلم‌های این مقاله</h2>
            <div class="post-media-carousel" id="mediaBlogCarousel">
                <div class="media-carousel-container" id="mediaBlogCarouselContainer">
                    <?php foreach ($media_files as $index => $media): ?>
                        <div class="media-carousel-item" data-index="<?php echo $index; ?>">
                            <?php if (preg_match('/\.(mp4|webm|mov)$/i', $media)): ?>
                                <video controls style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                                    <source src="<?php echo htmlspecialchars($media); ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="<?php echo htmlspecialchars($media); ?>" alt="تصویر مقاله" style="cursor: pointer;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($media_files) > 1): ?>
                    <button class="media-carousel-button prev" onclick="prevMediaSlide()">‹</button>
                    <button class="media-carousel-button next" onclick="nextMediaSlide()">›</button>
                    
                    <div class="media-carousel-controls" id="mediaBlogCarouselControls">
                        <?php foreach ($media_files as $index => $media): ?>
                            <span class="media-carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                  onclick="goToMediaSlide(<?php echo $index; ?>)"></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="media-counter">
                    <span id="currentMediaSlide">1</span> / <span id="totalMediaSlides"><?php echo count($media_files); ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- نمایش ویدیو خارجی -->
        <?php if (!empty($post['video_link'])): ?>
            <div style="margin: 3rem 0;">
                <h2>ویدیو مرتبط</h2>
                <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; background: black; margin: 2rem 0;">
                    <?php
                    $video_url = $post['video_link'];
                    
                    // تشخیص نوع ویدیو
                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                        // یوتیوب
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches);
                        $video_id = $matches[1] ?? '';
                        if ($video_id) {
                            $embed_url = "https://www.youtube.com/embed/{$video_id}";
                        }
                    } elseif (strpos($video_url, 'vimeo.com') !== false) {
                        // ویمیو
                        preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches);
                        $video_id = $matches[1] ?? '';
                        if ($video_id) {
                            $embed_url = "https://player.vimeo.com/video/{$video_id}";
                        }
                    } else {
                        $embed_url = $video_url;
                    }
                    
                    if (!empty($embed_url)): ?>
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 12px;" 
                            src="<?php echo htmlspecialchars($embed_url); ?>" 
                            frameborder="0" allowfullscreen>
                        </iframe>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <h2>متن کامل</h2>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 pixhom. تمام حقوق محفوظ است. | ساخته شده با ❤️ توسط تیم توسعه</p>
        </div>
    </footer>

    <script>
        let mediaCurrentIndex = 0;
        let mediaTotalSlides = document.querySelectorAll('.media-carousel-item').length;

        function showMediaSlide(index) {
            const container = document.getElementById('mediaBlogCarouselContainer');
            const dots = document.querySelectorAll('.media-carousel-dot');
            
            if (mediaTotalSlides === 0) return;
            
            // تنظیم index
            if (index >= mediaTotalSlides) {
                mediaCurrentIndex = 0;
            } else if (index < 0) {
                mediaCurrentIndex = mediaTotalSlides - 1;
            } else {
                mediaCurrentIndex = index;
            }
            
            // حرکت carousel
            if (container) {
                const offset = -mediaCurrentIndex * 100;
                container.style.transform = `translateX(${offset}%)`;
            }
            
            // به‌روزرسانی dots
            dots.forEach((dot, idx) => {
                if (idx === mediaCurrentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
            
            // به‌روزرسانی counter
            const currentSlideEl = document.getElementById('currentMediaSlide');
            if (currentSlideEl) {
                currentSlideEl.textContent = mediaCurrentIndex + 1;
            }
        }

        function nextMediaSlide() {
            showMediaSlide(mediaCurrentIndex + 1);
        }

        function prevMediaSlide() {
            showMediaSlide(mediaCurrentIndex - 1);
        }

        function goToMediaSlide(index) {
            showMediaSlide(index);
        }

        // دکمه‌های کیبوردی
        document.addEventListener('keydown', function(event) {
            const carousel = document.getElementById('mediaBlogCarousel');
            if (carousel) {
                if (event.key === 'ArrowRight') {
                    nextMediaSlide();
                } else if (event.key === 'ArrowLeft') {
                    prevMediaSlide();
                }
            }
        });

        // تغییر اسلاید با swipe
        let touchStartX = 0;
        let touchEndX = 0;

        const mediaCarouselContainer = document.getElementById('mediaBlogCarouselContainer');
        if (mediaCarouselContainer) {
            mediaCarouselContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            mediaCarouselContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) {
                    nextMediaSlide();
                } else if (touchEndX - touchStartX > 50) {
                    prevMediaSlide();
                }
            });
        }
    </script>
</body>
</html>

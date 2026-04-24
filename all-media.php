<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گالری رسانه - Pixhom</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .media-filter {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border);
            background-color: var(--background);
            color: var(--text-primary);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .filter-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #ec4899 0%, #7c3aed 100%);
            border-color: transparent;
            color: white;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .media-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--background);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border);
        }

        .media-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.2);
        }

        .media-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #000;
        }

        .media-info {
            padding: 12px;
            background-color: var(--surface);
        }

        .media-name {
            font-size: 0.9rem;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }

        .media-type {
            display: inline-block;
            font-size: 0.75rem;
            background-color: var(--accent);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .media-type.video {
            background-color: #3b82f6;
        }

        .media-type.image {
            background-color: #10b981;
        }

        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            direction: ltr;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
        }

        .lightbox-image,
        .lightbox-video {
            max-width: 100%;
            max-height: 85vh;
            border-radius: 8px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
            color: white;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .lightbox-close:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            color: white;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .lightbox-nav:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .lightbox-prev {
            left: 20px;
        }

        .lightbox-next {
            right: 20px;
        }

        .media-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .media-empty p {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .media-thumbnail {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.html'; ?>

    <div class="container" style="padding-top: 100px; padding-bottom: 60px;">
        <h1 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #ec4899 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">گالری رسانه</h1>

        <!-- فیلتر نوع رسانه -->
        <div class="media-filter">
            <button class="filter-btn active" onclick="filterMedia('all')">همه</button>
            <button class="filter-btn" onclick="filterMedia('image')">عکس</button>
            <button class="filter-btn" onclick="filterMedia('video')">ویدیو</button>
        </div>

        <!-- شبکه رسانه -->
        <div id="mediaGrid" class="media-grid"></div>

        <!-- پیام خالی -->
        <div id="emptyMessage" class="media-empty" style="display: none;">
            <p>رسانه‌ای یافت نشد</p>
        </div>
    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">✕</button>
        <div class="lightbox-content">
            <img id="lightboxImage" class="lightbox-image" style="display: none;">
            <video id="lightboxVideo" class="lightbox-video" style="display: none; width: 100%; height: auto; outline: none;" controls></video>
        </div>
        <button class="lightbox-nav lightbox-prev" onclick="previousMedia()">❮</button>
        <button class="lightbox-nav lightbox-next" onclick="nextMedia()">❯</button>
    </div>

    <?php include 'components/footer.html'; ?>

    <script>
        let mediaData = [];
        let filteredData = [];
        let currentFilter = 'all';
        let currentLightboxIndex = 0;

        async function loadMediaData() {
            try {
                const response = await fetch("api/get-media.php");
                const data = await response.json();
                
                console.log("[v0] API Response:", data);
                
                mediaData = data.map((item) => {
                    const ext = item.file_path.split('.').pop().toLowerCase();
                    const type = ['mp4', 'webm', 'mov', 'avi', 'mkv', 'flv'].includes(ext) ? 'video' : 'image';
                    
                    return {
                        id: item.id,
                        name: item.file_name,
                        url: item.file_path,
                        type: type,
                        uploadedAt: item.uploaded_at
                    };
                });

                console.log("[v0] Processed media:", mediaData);

                // مرتب‌سازی بر اساس تاریخ (جدیدترین اول)
                mediaData.sort((a, b) => new Date(b.uploadedAt) - new Date(a.uploadedAt));
                
                renderMedia('all');
            } catch (error) {
                console.error('[v0] Error loading media:', error);
                document.getElementById('mediaGrid').innerHTML = '<p style="color: #ef4444;">خطا در بارگذاری رسانه‌ها</p>';
            }
        }

        function filterMedia(type) {
            currentFilter = type;
            
            // بروزرسانی دکمه‌های فیلتر
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            renderMedia(type);
        }

        function renderMedia(filter) {
            const grid = document.getElementById('mediaGrid');
            const emptyMsg = document.getElementById('emptyMessage');
            
            filteredData = filter === 'all' 
                ? mediaData 
                : mediaData.filter(item => item.type === filter);

            if (filteredData.length === 0) {
                grid.innerHTML = '';
                emptyMsg.style.display = 'block';
                return;
            }

            emptyMsg.style.display = 'none';
            grid.innerHTML = filteredData.map((item, index) => `
                <div class="media-item" onclick="openLightbox(${index})">
                    <div style="position: relative;">
                        ${item.type === 'video' 
                            ? `<video class="media-thumbnail" preload="metadata"><source src="${item.url}"></video><div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 2rem; color: white;">▶</div>`
                            : `<img class="media-thumbnail" src="${item.url}" alt="${item.name}">`
                        }
                    </div>
                    <div class="media-info">
                        <div class="media-name" title="${item.name}">${item.name}</div>
                        <span class="media-type ${item.type}">${item.type === 'video' ? 'ویدیو' : 'عکس'}</span>
                    </div>
                </div>
            `).join('');
        }

        function openLightbox(index) {
            currentLightboxIndex = index;
            const item = filteredData[index];
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            const lightboxVideo = document.getElementById('lightboxVideo');

            if (item.type === 'video') {
                lightboxImage.style.display = 'none';
                lightboxVideo.style.display = 'block';
                lightboxVideo.src = item.url;
            } else {
                lightboxVideo.style.display = 'none';
                lightboxImage.style.display = 'block';
                lightboxImage.src = item.url;
            }

            lightbox.classList.add('active');
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.getElementById('lightboxVideo').pause();
        }

        function previousMedia() {
            currentLightboxIndex = (currentLightboxIndex - 1 + filteredData.length) % filteredData.length;
            openLightbox(currentLightboxIndex);
        }

        function nextMedia() {
            currentLightboxIndex = (currentLightboxIndex + 1) % filteredData.length;
            openLightbox(currentLightboxIndex);
        }

        // بستن lightbox با کلید Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') previousMedia();
            if (e.key === 'ArrowRight') nextMedia();
        });

        // بارگذاری داده‌ها هنگام لود صفحه
        document.addEventListener('DOMContentLoaded', loadMediaData);
    </script>
</body>
</html>
